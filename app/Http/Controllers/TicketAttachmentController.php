<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketAttachment;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class TicketAttachmentController extends Controller
{
    public function view(Ticket $ticket, TicketAttachment $attachment): BinaryFileResponse
    {
        $this->authorizeAccess($ticket, $attachment);

        $absolutePath = Storage::disk('local')->path($attachment->file_path);
        if (!is_file($absolutePath)) {
            abort(404);
        }

        return response()->file($absolutePath, [
            'Content-Type' => $attachment->file_type ?: 'application/octet-stream',
            'Content-Disposition' => 'inline; filename="' . $attachment->file_name . '"',
        ]);
    }

    public function download(Ticket $ticket, TicketAttachment $attachment): BinaryFileResponse
    {
        $this->authorizeAccess($ticket, $attachment);

        $absolutePath = Storage::disk('local')->path($attachment->file_path);
        if (!is_file($absolutePath)) {
            abort(404);
        }

        return response()->download($absolutePath, $attachment->file_name, [
            'Content-Type' => $attachment->file_type ?: 'application/octet-stream',
        ]);
    }

    private function authorizeAccess(Ticket $ticket, TicketAttachment $attachment): void
    {
        $user = Auth::user();
        if (!$user instanceof User) {
            abort(403, 'Unauthorized');
        }

        if ($attachment->ticket_id !== $ticket->id) {
            abort(404);
        }

        if ($user->isUser() && $ticket->user_id !== $user->id) {
            abort(403, 'Unauthorized');
        }
    }
}
