<?php

namespace App\Mail;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Invoice $invoice
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Invoice ' . $this->invoice->invoice_number . ' from ' . (config('app.name') ?: 'Zam Zam'),
            replyTo: [config('mail.from.address')],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.invoice',
        );
    }

    /**
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        $disk = \App\Services\InvoicePdfService::INVOICES_DISK;
        $path = $this->invoice->pdf_path;
        
        // Use pdf_path with fallback to generation if it doesn't exist
        if (!$path || !\Illuminate\Support\Facades\Storage::disk($disk)->exists($path)) {
            $path = app(\App\Services\InvoicePdfService::class)->ensurePdfExists($this->invoice);
        } else {
            $path = \Illuminate\Support\Facades\Storage::disk($disk)->path($path);
        }
        
        $filename = preg_replace('/[^a-zA-Z0-9\-.]/', '-', $this->invoice->invoice_number) . '.pdf';
        
        return [
            Attachment::fromPath($path)->as($filename)->withMime('application/pdf'),
        ];
    }
}
