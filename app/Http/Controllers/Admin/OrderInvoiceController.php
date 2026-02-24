<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Order;
use Illuminate\Http\Request;
use Mccarlosen\LaravelMpdf\Facades\LaravelMpdf as PDF;

class OrderInvoiceController extends Controller
{
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

        // Generate a potential invoice number (User can override or we strictly enforce?)
        // Let's suggest one
        $nextInvoiceNumber = 'INV-' . date('Y') . '-' . str_pad(Invoice::count() + 1, 4, '0', STR_PAD_LEFT);

        return view('admin.orders.invoice.create', compact('order', 'items', 'nextInvoiceNumber'));
    }

    // Store the invoice in database
    public function store(Request $request, Order $order)
    {
        $validated = $request->validate([
            'invoice_number' => 'required|unique:invoices,invoice_number',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date',
            'notes' => 'nullable|string',
            'discount_total' => 'nullable|numeric|min:0',
            'shipping_amount' => 'nullable|numeric|min:0',
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
        $grandTotal = 0;

        $invoice = Invoice::create([
            'order_id' => $order->id,
            'invoice_number' => $validated['invoice_number'],
            'invoice_date' => $validated['invoice_date'],
            'due_date' => $validated['due_date'],
            'notes' => $validated['notes'] ?? null,
            'discount_total' => $validated['discount_total'] ?? 0,
            'shipping_amount' => $validated['shipping_amount'] ?? 0,
            'subtotal' => 0, // Will update after calculating items
            'tax_total' => 0,
            'total' => 0,
            'status' => 'draft',
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

        // Grand total = Subtotal + Tax - Discount + Shipping
        $grandTotal = max(0, $subtotal + $taxTotal - ($validated['discount_total'] ?? 0) + ($validated['shipping_amount'] ?? 0));

        $invoice->update([
            'subtotal' => $subtotal,
            'tax_total' => $taxTotal,
            'total' => $grandTotal,
        ]);

        return redirect()->route('admin.invoices.show', $invoice)->with('success', 'Invoice generated successfully.');
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['items', 'order.user']);
        return view('admin.invoices.show', compact('invoice'));
    }

    public function print(Invoice $invoice)
    {
        $invoice->load(['items.product.unit', 'items.product.tax', 'order.user']);

        // Map data to the structure required by invoice.blade.php
        $order = $invoice->order;
        
        $settings = \App\Models\SystemSetting::where('group', 'general')->pluck('value', 'key');
        
        $paymentInstructions = 'Method: ' . strtoupper(str_replace('_', ' ', $order->payment_method));
        $offlineMethod = \App\Models\OfflinePaymentMethod::where('name', $order->payment_method)->first();
        if ($offlineMethod) {
            $paymentInstructions = "Method: " . $offlineMethod->name . "<br>";
            if ($order->payment_data && is_array($order->payment_data)) {
                foreach ($order->payment_data as $k => $v) {
                    $paymentInstructions .= ucwords(str_replace('_', ' ', $k)) . ": <strong>" . e($v) . "</strong><br>";
                }
            }
        }

        $data = [
            'invoice_number' => $invoice->invoice_number,
            'invoice_date' => \Carbon\Carbon::parse($invoice->invoice_date)->format('d-M-Y'),
            'due_date' => \Carbon\Carbon::parse($invoice->due_date)->format('d-M-Y'),
            'source' => 'Web Order', // Or make dynamic if needed
            'purchase_order' => '',
            'salesperson' => auth()->user()->name, // Or store creator in invoice
            'payment_instructions' => $paymentInstructions, 
            'company' => [
                'name' => $settings['site_name'] ?? 'ZamZam Import and Export Inc.',
                'address' => nl2br(e($settings['address'] ?? "1-283 Morningside Ave\nScarborough, Ontario, M1E 3G1\nCanada")),
                'phone' => $settings['contact_phone'] ?? '+1 416-283-4488',
                'cell' => $settings['contact_cell'] ?? '+1 647-482-1133',
                'email' => $settings['contact_email'] ?? 'zamzamimport2023@gmail.com',
                'tax_id' => $settings['tax_id'] ?? '731247144RT0001',
            ],
            'partner' => [
                'name' => $order->user->name ?? $order->shipping_address['name'] ?? 'Guest',
                'address_1' => $order->shipping_address['address'] ?? '',
                'address_2' => ($order->shipping_address['city'] ?? '') . ' ' . ($order->shipping_address['zip'] ?? ''),
                'country' => $order->shipping_address['country'] ?? '',
                'phone' => $order->user->phone ?? $order->shipping_address['phone'] ?? ''
            ],
            'delivery' => [
                'name' => $order->shipping_address['name'] ?? 'Same as Partner',
                'address_1' => $order->shipping_address['address'] ?? '',
                'address_2' => ($order->shipping_address['city'] ?? '') . ' ' . ($order->shipping_address['zip'] ?? ''),
                'country' => $order->shipping_address['country'] ?? '',
                'phone' => $order->user->phone ?? $order->shipping_address['phone'] ?? ''
            ],
            'items' => $invoice->items->map(function ($item, $index) {
                // Determine image path
                $imagePath = public_path('images/placeholder.jpg'); // Default
                if ($item->product && $item->product->image) {
                     // Assuming product image is stored in storage/app/public or similar and symlinked to public/storage
                     // Or if it's directly in public/images
                     // Adjust based on typical Laravel storage. 
                     // If using Storage::url(), it gives /storage/..., so public_path() . $url
                     
                     // Let's assume standard storage link: public/storage/products/image.jpg
                     // Check if file exists to avoid PDF errors
                     $potentialPath = public_path('storage/' . $item->product->image);
                     if (file_exists($potentialPath)) {
                         $imagePath = $potentialPath;
                     } else {
                         // Check if it's just in public/
                         $potentialPath2 = public_path($item->product->image);
                         if (file_exists($potentialPath2)) {
                             $imagePath = $potentialPath2;
                         }
                     }
                }

                return [
                    's_no' => $index + 1,
                    'quantity' => $item->quantity,
                    'image_path' => $imagePath,
                    'description' => $item->product_name,
                    'upc' => $item->product->product_code ?? '', 
                    'uom' => $item->product->unit->name ?? 'Unit',
                    'box_price' => $item->product->box_price ?? 0,
                    'unit_price' => $item->unit_price,
                    'amount' => $item->total_price,
                    'discount' => $item->discount_amount,
                    'tax' => $item->tax_amount,
                    'highlight_color' => $item->highlight_color,
                ];
            }),
            'total_shipped_qty' => $invoice->items->sum('quantity'),
            'subtotal' => $invoice->subtotal,
            'shipping' => $invoice->shipping_amount,
            'shipping_method' => $order->shipping_method_name,
            'hst' => $invoice->tax_total,
            'hst_base' => $invoice->subtotal, 
            'total' => $invoice->total,
        ];

        $pdf = PDF::loadView('pdf.invoice', compact('data'));
        
        // return $pdf->stream();
        return $pdf->stream('invoice-' . $invoice->invoice_number . '.pdf');
    }
}
