<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class TicketActivityNotification extends Notification
{
    use Queueable;

    public array $details;

    public function __construct(array $details)
    {
        $this->details = $details;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $user = Auth::user();
        $byUserId = $user instanceof User ? $user->id : null;
        $byUserName = $user instanceof User ? $user->name : null;

        return [
            'type' => $this->details['type'] ?? 'ticket_activity',
            'title' => $this->details['title'] ?? 'Tiket',
            'message' => $this->details['message'] ?? '',
            'ticket_id' => $this->details['ticket_id'] ?? null,
            'ticket_number' => $this->details['ticket_number'] ?? null,
            'url' => $this->details['url'] ?? null,
            'by_user_id' => $this->details['by_user_id'] ?? $byUserId,
            'by_user_name' => $this->details['by_user_name'] ?? $byUserName,
        ];
    }
}
