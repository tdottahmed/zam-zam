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

        $order->load(['items.product', 'invoice', 'creditNotes.items.product']);

        return Inertia::render('Orders/Show', [
            'order' => $order,
            'offlinePaymentMethods' => \App\Models\OfflinePaymentMethod::where('is_active', true)->get()
        ]);
    }

    public function submitPayment(Request $request, Order $order)
    {
        if ($order->user_id != Auth::id()) {
            abort(403);
        }

        // Must be an offline payment method to submit payment data this way
        $method = \App\Models\OfflinePaymentMethod::where('name', $order->payment_method)->first();

        if (!$method) {
            return back()->with('error', 'Payment submission is only available for active offline payment methods, or the method is invalid.');
        }

        $rules = [];
        $requiredFields = $method->required_fields ?? [];

        foreach ($requiredFields as $field) {
            $rule = [];
            if (!empty($field['is_required'])) {
                $rule[] = 'required';
            } else {
                $rule[] = 'nullable';
            }

            if (($field['type'] ?? 'text') === 'file') {
                $rule[] = 'file';
                $rule[] = 'max:10240'; // 10MB limit
            } elseif (($field['type'] ?? 'text') === 'number') {
                $rule[] = 'numeric';
            } else {
                $rule[] = 'string';
            }
            
            $rules['payment_data.' . $field['name']] = implode('|', $rule);
        }

        $validated = $request->validate($rules);

        $paymentData = $validated['payment_data'] ?? [];

        // Handle File Uploads
        foreach ($requiredFields as $field) {
            if (($field['type'] ?? 'text') === 'file' && $request->hasFile('payment_data.' . $field['name'])) {
                $path = $request->file('payment_data.' . $field['name'])->store('payment_receipts', 'public');
                $paymentData[$field['name']] = $path;
            }
        }

        $order->update([
            'payment_data' => $paymentData,
            'payment_status' => 'pending', // Keeps it pending so admin can verify
        ]);

        return back()->with('success', 'Payment details submitted successfully. We will verify and process your order soon.');
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
        $settings = \App\Models\SystemSetting::where('group', 'general')->pluck('value', 'key');

        $data = [
            'invoice_number' => $invoice->invoice_number,
            'invoice_date' => \Carbon\Carbon::parse($invoice->invoice_date)->format('d-M-Y'),
            'due_date' => \Carbon\Carbon::parse($invoice->due_date)->format('d-M-Y'),
            'source' => 'Web Order', 
            'purchase_order' => '',
            'salesperson' => 'System', 
            'payment_instructions' => 'Paid via ' . $order->payment_method,
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
                     $potentialPath = public_path('storage/' . $item->product->image);
                     if (file_exists($potentialPath)) {
                         $imagePath = $potentialPath;
                     } else {
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
                ];
            }),
            'total_shipped_qty' => $invoice->items->sum('quantity'),
            'subtotal' => $invoice->subtotal,
            'shipping' => $invoice->shipping_amount ?? 0,
            'shipping_method' => $order->shipping_method_name ?? '',
            'hst' => $invoice->tax_total,
            'hst_base' => $invoice->subtotal, 
            'total' => $invoice->total,
        ];

        $pdf = \Mccarlosen\LaravelMpdf\Facades\LaravelMpdf::loadView('pdf.invoice', compact('data'));
        
        return $pdf->stream('invoice-' . $invoice->invoice_number . '.pdf');
    }
}
