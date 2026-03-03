<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Mccarlosen\LaravelMpdf\Facades\LaravelMpdf as PDF;

class OrderPdfService
{
    /**
     * Build the data array for the PDF view.
     */
    public function buildPdfData(Order $order): array
    {
        $order->load(['items.product.unit', 'items.product.tax', 'user']);

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

        return [
            'order_number' => '#' . $order->id,
            'order_date' => \Carbon\Carbon::parse($order->created_at)->format('d-M-Y'),
            'source' => 'Web Order',
            'salesperson' => Auth::check() ? Auth::user()->name : 'Admin',
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
                'phone' => $order->user->phone ?? $order->shipping_address['phone'] ?? '',
            ],
            'delivery' => [
                'name' => $order->shipping_address['name'] ?? 'Same as Partner',
                'address_1' => $order->shipping_address['address'] ?? '',
                'address_2' => ($order->shipping_address['city'] ?? '') . ' ' . ($order->shipping_address['zip'] ?? ''),
                'country' => $order->shipping_address['country'] ?? '',
                'phone' => $order->user->phone ?? $order->shipping_address['phone'] ?? '',
            ],
            'items' => $order->items->map(function ($item, $index) {
                $imagePath = public_path('images/placeholder.jpg');
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
                    'discount' => 0, // Order items don't strictly have a discount attribute stored directly on line items unless specifically defined. We'll use 0 for now.
                    'tax' => 0, // Simplified tax per item unless calculated. 
                ];
            }),
            'total_shipped_qty' => $order->items->sum('quantity'),
            // Using order totals
            'subtotal' => $order->items->sum('total_price'), // assuming sum of total_prices gives subtotal
            'shipping' => $order->shipping_amount ?? 0,
            'shipping_method' => $order->shipping_method_name,
            'hst' => $order->tax_amount ?? 0,
            'hst_base' => $order->items->sum('total_price'),
            'total' => $order->total_amount,
        ];
    }

    /**
     * Generate PDF and stream it.
     */
    public function generate(Order $order): \Symfony\Component\HttpFoundation\Response
    {
        $data = $this->buildPdfData($order);
        $pdf = PDF::loadView('pdf.order', compact('data'));

        return $pdf->stream('order-' . $order->id . '.pdf');
    }
}
