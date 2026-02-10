<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with(['items.product']) // Eager load items and products if needed for list
            ->latest()
            ->paginate(10);

        return Inertia::render('Orders/Index', [
            'orders' => $orders
        ]);
    }

    public function show(Order $order)
    {
        // Ensure the order belongs to the authenticated user
        if ($order->user_id != Auth::id()) {
            abort(403);
        }

        $order->load(['items.product', 'invoice']);

        return Inertia::render('Orders/Show', [
            'order' => $order
        ]);
    }

    public function downloadInvoice(Order $order)
    {
        // Ensure the order belongs to the authenticated user
        if ($order->user_id != Auth::id()) {
            abort(403);
        }

        $order->load(['invoice.items.product.unit', 'invoice.items.product.tax', 'user', 'items.product']);

        $invoice = $order->invoice;

        if (!$invoice) {
            return back()->with('error', 'Invoice not generated yet.');
        }

        // Map data to the structure required by invoice.blade.php
        // Reusing logic from OrderInvoiceController but adapted for user context if needed
        // For now, strict reuse of invoice data
        
        $data = [
            'invoice_number' => $invoice->invoice_number,
            'invoice_date' => $invoice->invoice_date->format('d-M-Y'),
            'due_date' => $invoice->due_date->format('d-M-Y'),
            'source' => 'Web Order', 
            'purchase_order' => '',
            'salesperson' => 'System', 
            'payment_instructions' => 'Paid via ' . ucfirst($order->payment_method), 
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
                    'taxes' => $item->tax_amount > 0 ? 'Taxable' : '' 
                ];
            }),
            'total_shipped_qty' => $invoice->items->sum('quantity'),
            'subtotal' => $invoice->subtotal,
            'hst' => $invoice->tax_total,
            'hst_base' => $invoice->subtotal, 
            'total' => $invoice->total,
        ];

        $pdf = \Mccarlosen\LaravelMpdf\Facades\LaravelMpdf::loadView('pdf.invoice', compact('data'));
        
        return $pdf->stream('invoice-' . $invoice->invoice_number . '.pdf');
    }
}
