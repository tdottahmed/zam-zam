<?php

namespace App\Services;

use App\Models\Invoice;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Mccarlosen\LaravelMpdf\Facades\LaravelMpdf as PDF;

class InvoicePdfService
{
    public const INVOICES_DISK = 'public';

    /**
     * Sanitized filename and relative path for an invoice PDF.
     * Format: invoices/{uuid}.pdf
     */
    public static function pdfPathForInvoice(Invoice $invoice): string
    {
        return 'invoices/' . \Illuminate\Support\Str::uuid()->toString() . '.pdf';
    }
    /**
     * Build the data array for the PDF view (shared between stream and save).
     */
    public function buildPdfData(Invoice $invoice): array
    {
        $invoice->load(['items.product.unit', 'items.product.tax', 'order.user']);
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

        return [
            'invoice_number' => $invoice->invoice_number,
            'ci' => $invoice->ci,
            'invoice_date' => \Carbon\Carbon::parse($invoice->invoice_date)->format('d-M-Y'),
            'due_date' => \Carbon\Carbon::parse($invoice->due_date)->format('d-M-Y'),
            'source' => 'Web Order',
            'purchase_order' => '',
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
            'items' => $invoice->items->map(function ($item, $index) {
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
                    'pcs_in_ctn' => $item->product ? ($item->product->pcs_in_ctn ?: 1) : 1,
                    'image_path' => $imagePath,
                    'description' => $item->product_name,
                    'upc' => $item->product->product_code ?? '',
                    'uom' => $item->product->pcs_in_ctn ? 'BOX ' . $item->product->pcs_in_ctn : 'Unit',
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
            'freight_charge' => $invoice->freight_charge,
            'advance_amount' => $invoice->advance_amount,
            'shipping_method' => $order->shipping_method_name,
            'hst' => $invoice->tax_total,
            'total_discount' => $invoice->discount_total,
            'hst_base' => $invoice->subtotal,
            'total' => $invoice->total,
            'balance_due' => $invoice->balance_due,
        ];
    }

    /**
     * Generate PDF and optionally save to disk. Returns relative path if saved, or response for stream.
     * Stored path format: {year}/{invoice_number}.pdf e.g. 2025/INV-2025-0001.pdf
     */
    public function generate(Invoice $invoice, bool $saveToDisk = false): string|\Symfony\Component\HttpFoundation\Response
    {
        $data = $this->buildPdfData($invoice);
        $pdf = PDF::loadView('pdf.invoice', compact('data'));

        if ($saveToDisk) {
            $path = self::pdfPathForInvoice($invoice);
            $disk = self::INVOICES_DISK;

            // If an old PDF exists, delete it before creating a new one to prevent orphaned files
            if ($invoice->pdf_path && $invoice->pdf_path !== $path && Storage::disk($disk)->exists($invoice->pdf_path)) {
                Storage::disk($disk)->delete($invoice->pdf_path);
            }

            // Ensure directory exists (e.g. storage/app/public/invoices)
            $fullPath = Storage::disk($disk)->path($path);
            $dir = dirname($fullPath);
            if (!is_dir($dir)) {
                if (!@mkdir($dir, 0755, true)) {
                    Log::error('InvoicePdfService: Failed to create directory', ['path' => $dir]);
                    throw new \RuntimeException('Unable to create invoice storage directory: ' . $dir);
                }
            }

            try {
                $pdf->save($fullPath);
            } catch (\Throwable $e) {
                Log::error('InvoicePdfService: Failed to save PDF', [
                    'invoice_id' => $invoice->id,
                    'path' => $fullPath,
                    'error' => $e->getMessage(),
                ]);
                throw $e;
            }

            if (!file_exists($fullPath)) {
                Log::error('InvoicePdfService: PDF file not found after save', ['path' => $fullPath]);
                throw new \RuntimeException('Invoice PDF was not written to disk.');
            }

            $invoice->update(['pdf_path' => $path]);
            return $path;
        }

        return $pdf->stream('invoice-' . $invoice->invoice_number . '.pdf');
    }

    /**
     * Get full filesystem path for an invoice PDF. Generates and saves if not present.
     */
    public function ensurePdfExists(Invoice $invoice): string
    {
        $disk = self::INVOICES_DISK;
        if ($invoice->pdf_path && Storage::disk($disk)->exists($invoice->pdf_path)) {
            return Storage::disk($disk)->path($invoice->pdf_path);
        }
        $this->generate($invoice, true);
        return Storage::disk($disk)->path($invoice->fresh()->pdf_path);
    }
}
