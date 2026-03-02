<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\InvoiceMail;
use App\Models\Invoice;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Invoice::with(['order.user']);

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('invoice_number', 'like', "%{$search}%")
                  ->orWhereHas('order.user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
        }

        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }
        
        $invoices = $query->latest()->paginate(10);

        return view('admin.invoices.index', compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     * In this case, selecting an order to invoice.
     */
    public function create()
    {
        // Get orders that are confirmed but don't have an invoice yet (optional logic)
        // For now, let's list all orders that are not cancelled
        $orders = Order::with('user')
            ->where('status', '!=', 'cancelled')
            ->whereDoesntHave('invoice') // Assuming one invoice per order
            ->latest()
            ->paginate(10);

        return view('admin.invoices.create_select_order', compact('orders'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // This might be used if we create invoice directly here, 
        // but currently we redirect to OrderInvoiceController@create
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        $invoice->load(['items', 'order.user']);
        return view('admin.invoices.show', compact('invoice'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoice $invoice)
    {
        $invoice->load(['items', 'order.user']);
        return view('admin.invoices.edit', compact('invoice'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'invoice_date' => 'required|date',
            'due_date' => 'required|date',
            'status' => 'required|in:draft,sent,paid,overdue,cancelled',
            'notes' => 'nullable|string',
            'discount_total' => 'nullable|numeric|min:0',
            'shipping_amount' => 'nullable|numeric|min:0',
            'items' => 'required|array',
            'items.*.selected' => 'sometimes|in:on,1,true',
            'items.*.quantity' => 'required_with:items.*.selected|numeric|min:0.01',
            'items.*.price' => 'required_with:items.*.selected|numeric|min:0',
            'items.*.discount' => 'nullable|numeric|min:0',
        ]);

        // Filter selected items
        $itemsData = collect($request->items)->filter(function ($item) {
            return isset($item['selected']); 
        });

        if ($itemsData->isEmpty()) {
            return back()->withErrors(['items' => 'At least one item must be retained in the invoice.']);
        }

        $subtotal = 0;
        $taxTotal = 0;

        // 1. Update Items
        foreach ($itemsData as $itemId => $data) {
            $invoiceItem = $invoice->items()->find($itemId);
            
            if (!$invoiceItem) continue;

            $quantity = $data['quantity'];
            $unitPrice = $data['price'];
            $discountAmount = $data['discount'] ?? 0;
            
            // Calculate line total
            $lineTotal = ($quantity * $unitPrice) - $discountAmount;
            
            // Recalculate Tax
            $product = $invoiceItem->product;
            $taxRate = $product && $product->tax ? $product->tax->value : 0;
            $lineTax = max(0, $lineTotal) * ($taxRate / 100);

            $invoiceItem->update([
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'discount_amount' => $discountAmount,
                'total_price' => $lineTotal,
                'tax_amount' => $lineTax,
            ]);

            $subtotal += $lineTotal;
            $taxTotal += $lineTax;
        }

        // 2. Handle Deleted Items (unchecked items)
        $submittedItemIds = $itemsData->keys()->toArray();
        $invoice->items()->whereNotIn('id', $submittedItemIds)->delete();

        // 3. Update Invoice Totals
        $grandTotal = max(0, $subtotal + $taxTotal - ($validated['discount_total'] ?? 0) + ($validated['shipping_amount'] ?? 0));

        $invoice->update([
            'invoice_date' => $validated['invoice_date'],
            'due_date' => $validated['due_date'],
            'status' => $validated['status'],
            'notes' => $validated['notes'],
            'subtotal' => $subtotal,
            'tax_total' => $taxTotal,
            'discount_total' => $validated['discount_total'] ?? 0,
            'shipping_amount' => $validated['shipping_amount'] ?? 0,
            'total' => $grandTotal,
        ]);

        // Delete previous PDF file and create new one
        $this->deleteInvoicePdf($invoice);
        dispatch(new \App\Jobs\GenerateInvoicePdf($invoice->fresh()));

        return redirect()->route('admin.invoices.index')->with('success', 'Invoice updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        $this->deleteInvoicePdf($invoice);
        $invoice->items()->delete();
        $invoice->delete();

        return redirect()->route('admin.invoices.index')->with('success', 'Invoice deleted successfully.');
    }

    /**
     * Bulk delete selected invoices.
     */
    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:invoices,id',
        ]);

        $invoices = Invoice::whereIn('id', $request->ids)->get();
        foreach ($invoices as $invoice) {
            $this->deleteInvoicePdf($invoice);
            $invoice->items()->delete();
            $invoice->delete();
        }

        $count = count($request->ids);
        return redirect()->route('admin.invoices.index')
            ->with('success', "{$count} invoice(s) deleted successfully.");
    }

    /**
     * Bulk send invoice emails to customers.
     */
    public function bulkSendEmail(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:invoices,id',
        ]);

        $invoices = Invoice::with(['order.user'])->whereIn('id', $request->ids)->get();
        $sent = 0;
        $skipped = [];

        foreach ($invoices as $invoice) {
            $email = $invoice->order->user->email ?? ($invoice->order->shipping_address['email'] ?? null);
            if (!$email) {
                $skipped[] = $invoice->invoice_number;
                continue;
            }
            try {
                Mail::to($email)->send(new InvoiceMail($invoice));
                $sent++;
            } catch (\Throwable $e) {
                Log::warning('Invoice email failed: ' . $invoice->invoice_number . ' - ' . $e->getMessage());
                $skipped[] = $invoice->invoice_number;
            }
        }

        if (count($skipped) > 0) {
            return redirect()->route('admin.invoices.index')
                ->with('warning', "{$sent} invoice(s) sent. Could not send: " . implode(', ', $skipped) . (count($skipped) > 0 ? ' (missing email or send failed).' : ''));
        }

        return redirect()->route('admin.invoices.index')
            ->with('success', "{$sent} invoice(s) sent successfully.");
    }

    private function deleteInvoicePdf(Invoice $invoice): void
    {
        $disk = \App\Services\InvoicePdfService::INVOICES_DISK;
        if ($invoice->pdf_path && Storage::disk($disk)->exists($invoice->pdf_path)) {
            Storage::disk($disk)->delete($invoice->pdf_path);
        }
    }
}
