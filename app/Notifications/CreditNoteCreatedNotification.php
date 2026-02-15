<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CreditNoteCreatedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public $creditNote;

    /**
     * Create a new notification instance.
     */
    public function __construct($creditNote)
    {
        $this->creditNote = $creditNote;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'credit_note_id' => $this->creditNote->id,
            'order_id' => $this->creditNote->order_id,
            'message' => 'New Credit Note Request for Order #' . $this->creditNote->order_id,
            'amount' => $this->creditNote->grand_total,
            'created_at' => $this->creditNote->created_at,
            'type' => 'credit_note', 
        ];
    }
}
