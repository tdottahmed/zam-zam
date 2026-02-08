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
            'items' => 'required|array',
            'items.*.selected' => 'sometimes|in:on,1,true',
            'items.*.quantity' => 'required_with:items.*.selected|numeric|min:0.01',
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
            'notes' => $validated['notes'],
            'subtotal' => 0, // Will update after calculating items
            'tax_total' => 0,
            'total' => 0,
            'status' => 'draft',
        ]);

        foreach ($selectedItems as $orderItemId => $data) {
            $orderItem = $order->items->find($orderItemId);
            
            if (!$orderItem) continue;

            $quantity = $data['quantity'];
            $unitPrice = $orderItem->unit_price;
            $lineTotal = $quantity * $unitPrice;
            
            $product = $orderItem->product;
            $taxRate = $product && $product->tax ? $product->tax->value : 0;
            $lineTax = $unitPrice * $quantity * ($taxRate / 100);

            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'product_id' => $orderItem->product_id,
                'product_name' => $orderItem->product_name,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'total_price' => $lineTotal,
                'tax_amount' => $lineTax,
            ]);

            $subtotal += $lineTotal;
            $taxTotal += $lineTax;
        }

        $grandTotal = $subtotal + $taxTotal;

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
        
        $data = [
            'invoice_number' => $invoice->invoice_number,
            'invoice_date' => $invoice->invoice_date->format('d-M-Y'),
            'due_date' => $invoice->due_date->format('d-M-Y'),
            'source' => 'Web Order', // Or make dynamic if needed
            'purchase_order' => '',
            'salesperson' => auth()->user()->name, // Or store creator in invoice
            'payment_instructions' => 'Please pay via Bank Transfer', 
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
                return [
                    's_no' => $index + 1,
                    'quantity' => $item->quantity,
                    'description' => $item->product_name,
                    'upc' => $item->product->product_code ?? '', 
                    'uom' => $item->product->unit->name ?? 'Unit',
                    'box_price' => $item->product->box_price ?? 0,
                    'unit_price' => $item->unit_price,
                    'amount' => $item->total_price,
                    'taxes' => $item->tax_amount > 0 ? 'Taxable' : '' // Simplified tax display
                ];
            }),
            'total_shipped_qty' => $invoice->items->sum('quantity'),
            'subtotal' => $invoice->subtotal,
            'hst' => $invoice->tax_total,
            'hst_base' => $invoice->subtotal, 
            'total' => $invoice->total,
        ];

        $pdf = PDF::loadView('pdf.invoice', compact('data'));
        
        // return $pdf->stream();
        return $pdf->stream('invoice-' . $invoice->invoice_number . '.pdf');
    }
}
