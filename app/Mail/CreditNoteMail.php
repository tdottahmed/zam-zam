<?php

namespace App\Mail;

use App\Models\CreditNote;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CreditNoteMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public CreditNote $creditNote
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Credit Note ' . $this->creditNote->credit_note_number . ' from ' . (config('app.name') ?: 'Zam Zam'),
            replyTo: [config('mail.from.address')],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.credit_note',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
