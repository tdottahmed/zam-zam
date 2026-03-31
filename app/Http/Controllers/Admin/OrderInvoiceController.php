<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Order;
use App\Services\InvoicePdfService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class OrderInvoiceController extends Controller
{
    public function generateNumber()
    {
        $lastInvoice = Invoice::latest('id')->first();
        $nextId = $lastInvoice ? $lastInvoice->id + 1 : 1;
        $nextInvoiceNumber = 'INV-' . date('Y') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
        
        // Ensure uniqueness by checking if it exists
        while (Invoice::where('invoice_number', $nextInvoiceNumber)->exists()) {
            $nextId++;
            $nextInvoiceNumber = 'INV-' . date('Y') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
        }

        return response()->json(['invoice_number' => $nextInvoiceNumber]);
    }
    public function __construct(
        protected InvoicePdfService $invoicePdf
    ) {}
    // Show the form to create an invoice
    public function create(Order $order)
    {
        $order->load(['items.product.unit', 'items.product.tax', 'user']);
        
        // Prepare items with stock check
        $items = $order->items->map(function ($item) {
            $product = $item->product;
            $stock = $product ? $product->quantity : 0;
            $status = 'insufficient';
            
            if (!$product) {
                $status = 'deleted';
            } elseif ($stock >= $item->quantity) {
                $status = 'available';
            }

            return [
                'order_item_id' => $item->id,
                'item' => $item,
                'stock' => $stock,
                'status' => $status
            ];
        });

        $nextInvoiceNumber = 'INV-' . date('Y') . '-' . str_pad(Invoice::count() + 1, 4, '0', STR_PAD_LEFT);

        return view('admin.orders.invoice.create', compact('order', 'items', 'nextInvoiceNumber'));
    }

    // Store the invoice in database
    public function store(Request $request, Order $order)
    {
        $validated = $request->validate([
            'invoice_number' => 'required|unique:invoices,invoice_number',
            'ci' => 'nullable|string|max:255',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date',
            'notes' => 'nullable|string',
            'discount_total' => 'nullable|numeric|min:0',
            'freight_charge' => 'nullable|numeric|min:0',
            'items' => 'required|array',
            'items.*.selected' => 'sometimes|in:on,1,true',
            'items.*.quantity' => 'required_with:items.*.selected|numeric|min:0.01',
            'items.*.price' => 'required_with:items.*.selected|numeric|min:0',
            'items.*.discount' => 'nullable|numeric|min:0',
            'items.*.highlight_color' => 'nullable|string|max:7',
        ]);

        // Filter only selected items
        $selectedItems = collect($request->items)->filter(function ($item) {
            return isset($item['selected']);
        });

        if ($selectedItems->isEmpty()) {
            return back()->withErrors(['items' => 'Please select at least one item to invoice.']);
        }

        $order->load(['items.product.tax']);

        $subtotal = 0;
        $taxTotal = 0;
        // Grand total = Subtotal + Tax - Discount + Shipping + Freight Charge
        // This will be calculated after item processing
        $grandTotal = 0;

        // Create invoice first to get an ID for items
        $invoice = Invoice::create([
            'order_id' => $order->id,
            'invoice_number' => $validated['invoice_number'],
            'ci' => $validated['ci'] ?? null,
            'invoice_date' => $validated['invoice_date'],
            'due_date' => $validated['due_date'],
            'notes' => $validated['notes'] ?? null,
            'discount_total' => $validated['discount_total'] ?? 0,
            'freight_charge' => $validated['freight_charge'] ?? 0,
            'subtotal' => 0, // Will update after calculating items
            'tax_total' => 0,
            'total' => 0,
            'status' => 'completed',
        ]);

        foreach ($selectedItems as $orderItemId => $data) {
            $orderItem = $order->items->find($orderItemId);
            
            if (!$orderItem) continue;

            $quantity = $data['quantity'];
            $unitPrice = $data['price']; // Use edited price
            $discountAmount = $data['discount'] ?? 0;
            
            // Calculate line total: (Qty * Price) - Discount
            $lineTotal = ($quantity * $unitPrice) - $discountAmount;
            
            $product = $orderItem->product;
            $taxRate = $product && $product->tax ? $product->tax->value : 0;
            // Tax is usually calculated on the discounted amount
            $lineTax = max(0, $lineTotal) * ($taxRate / 100);

            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'product_id' => $orderItem->product_id,
                'product_name' => $orderItem->product_name,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'discount_amount' => $discountAmount,
                'total_price' => $lineTotal,
                'tax_amount' => $lineTax,
                'highlight_color' => $data['highlight_color'] ?? null,
            ]);

            $subtotal += $lineTotal;
            $taxTotal += $lineTax;
        }

        // Grand total = Subtotal + Tax - Discount
        $grandTotal = max(0, $subtotal + $taxTotal - ($validated['discount_total'] ?? 0));

        $invoice->update([
            'subtotal' => $subtotal,
            'tax_total' => $taxTotal,
            'total' => $grandTotal,
        ]);

        // Generate and save PDF with UUID to storage/app/public/invoices in the background
        dispatch(new \App\Jobs\GenerateInvoicePdf($invoice));

        // Optionally send the email right after creation (Add a delay so PDF has time to generate)
        $email = $order->user->email ?? ($order->shipping_address['email'] ?? null);
        if ($email) {
            dispatch(new \App\Jobs\SendInvoiceEmail($invoice, $email))->delay(now()->addSeconds(5));
        }

        return redirect()->route('admin.invoices.show', $invoice)->with('success', 'Invoice generated successfully.');
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['items', 'order.user']);
        return view('admin.invoices.show', compact('invoice'));
    }

    /**
     * Stream PDF in browser. Uses stored file if available, otherwise generates on-the-fly.
     */
    public function print(Invoice $invoice)
    {
        $disk = \App\Services\InvoicePdfService::INVOICES_DISK;
        
        if ($invoice->pdf_path && \Illuminate\Support\Facades\Storage::disk($disk)->exists($invoice->pdf_path)) {
            $fullPath = \Illuminate\Support\Facades\Storage::disk($disk)->path($invoice->pdf_path);
            return response()->file($fullPath, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="invoice-' . preg_replace('/[^a-zA-Z0-9\-]/', '-', $invoice->invoice_number) . '.pdf"',
            ]);
        }

        // Fallback to on-the-fly generation if file doesn't exist
        return $this->invoicePdf->generate($invoice, false);
    }

    /**
     * Download PDF. Uses stored file if available, otherwise generates and saves.
     */
    public function download(Invoice $invoice)
    {
        $fullPath = $this->invoicePdf->ensurePdfExists($invoice);
        $filename = 'invoice-' . preg_replace('/[^a-zA-Z0-9\-]/', '-', $invoice->invoice_number) . '.pdf';
        return response()->download($fullPath, $filename, [
            'Content-Type' => 'application/pdf',
        ]);
    }
}
