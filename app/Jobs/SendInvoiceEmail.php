<?php

namespace App\Jobs;

use App\Mail\InvoiceMail;
use App\Models\Invoice;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendInvoiceEmail implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Invoice $invoice,
        public string $email
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Mail::to($this->email)->send(new InvoiceMail($this->invoice));
            
            // Optionally update invoice status if needed
            if ($this->invoice->status === 'draft') {
                $this->invoice->update(['status' => 'sent']);
            }
        } catch (\Throwable $e) {
            Log::error('Invoice email failed in Job: ' . $this->invoice->invoice_number, [
                'error' => $e->getMessage(),
                'email' => $this->email
            ]);
            
            // Re-throw to allow queue retry mechanism to handle it
            throw $e;
        }
    }
}

