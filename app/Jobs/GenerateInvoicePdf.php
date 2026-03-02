<?php

namespace App\Jobs;

use App\Models\Invoice;
use App\Services\InvoicePdfService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateInvoicePdf implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Invoice $invoice
    ) {}

    /**
     * Execute the job.
     */
    public function handle(InvoicePdfService $pdfService): void
    {
        $pdfService->generate($this->invoice, true);
    }
}

