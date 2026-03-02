<?php

namespace App\Jobs;

use App\Mail\CreditNoteMail;
use App\Models\CreditNote;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendCreditNoteEmail implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public CreditNote $creditNote,
        public string $email
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Mail::to($this->email)->send(new CreditNoteMail($this->creditNote));
        } catch (\Throwable $e) {
            Log::error('Credit Note email failed in Job: ' . $this->creditNote->credit_note_number, [
                'error' => $e->getMessage(),
                'email' => $this->email
            ]);
            
            throw $e;
        }
    }
}
