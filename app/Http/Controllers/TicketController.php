<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Category;
use App\Models\TicketResponse;
use App\Models\User;
use App\Notifications\TicketActivityNotification;
use Illuminate\Support\Facades\Notification;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $query = Ticket::with(['user', 'category', 'assignedTo']);

        // Apply filters from query parameters
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        if ($request->has('priority') && $request->priority != '') {
            $query->where('priority', $request->priority);
        }

        if ($request->has('assigned_to') && $request->assigned_to != '') {
            $query->where('assigned_to', $request->assigned_to);
        }

        // User-specific filtering
        if ($user->isUser()) {
            $query->where('user_id', $user->id);
        } elseif ($user->isTechnician()) {
            // If no specific filter, show assigned or unassigned tickets
            if (!$request->has('assigned_to')) {
                $query->where(function ($q) use ($user) {
                    $q->where('assigned_to', $user->id)
                        ->orWhereNull('assigned_to');
                });
            }
        }

        $tickets = $query->latest()->paginate(15)->withQueryString();

        return view('tickets.index', compact('tickets'));
    }

    public function create()
    {
        $categories = Category::active()->get();
        return view('tickets.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'priority' => 'required|in:low,medium,high,critical',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['status'] = 'open';

        $ticket = Ticket::createWithUniqueTicketNumber($validated);

        // Notify admins, general managers, and technicians about new ticket
        $recipients = User::whereIn('role', ['admin', 'general_manager', 'technician'])->get();
        $this->sendTicketNotification(
            $recipients,
            'tiket_baru',
            $ticket,
            'Tiket baru dibuat',
            sprintf('Tiket %s dibuat oleh %s dengan prioritas %s', $ticket->ticket_number, auth()->user()->name, ucfirst($ticket->priority))
        );

        return redirect()->route('tickets.show', $ticket)
            ->with('success', 'Tiket berhasil dibuat!');
    }

    public function show(Ticket $ticket)
    {
        // Authorization check
        $user = auth()->user();
        if ($user->isUser() && $ticket->user_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        $ticket->load(['user', 'category', 'assignedTo', 'responses.user', 'attachments']);

        $technicians = User::where('role', 'technician')->get();

        return view('tickets.show', compact('ticket', 'technicians'));
    }

    public function update(Request $request, Ticket $ticket)
    {
        $user = auth()->user();

        if ($user->isAdmin() || $user->isTechnician()) {
            $previousStatus = $ticket->status;
            $previousAssigned = $ticket->assigned_to;

            $validated = $request->validate([
                'status' => 'nullable|in:open,in_progress,resolved,closed',
                'priority' => 'nullable|in:low,medium,high,critical',
                'assigned_to' => 'nullable|exists:users,id',
            ]);

            if (isset($validated['status'])) {
                if ($validated['status'] === 'resolved') {
                    $validated['resolved_at'] = now();
                } elseif ($validated['status'] === 'closed') {
                    $validated['closed_at'] = now();
                }
            }

            $ticket->update($validated);

            // Notify when status changes
            if (isset($validated['status']) && $validated['status'] !== $previousStatus) {
                $recipients = [$ticket->user, $ticket->assignedTo];
                $this->sendTicketNotification(
                    $recipients,
                    'status_diperbarui',
                    $ticket,
                    'Status tiket diperbarui',
                    sprintf('Status tiket %s berubah dari %s menjadi %s', $ticket->ticket_number, $previousStatus, $ticket->status)
                );
            }

            // Notify when assignment changes
            if (isset($validated['assigned_to']) && $validated['assigned_to'] !== $previousAssigned) {
                $assignedUser = User::find($validated['assigned_to']);
                $recipients = [$assignedUser, $ticket->user];
                $this->sendTicketNotification(
                    $recipients,
                    'tiket_ditugaskan',
                    $ticket,
                    'Tiket ditugaskan',
                    sprintf('Tiket %s ditugaskan kepada %s', $ticket->ticket_number, optional($assignedUser)->name)
                );
            }

            return back()->with('success', 'Tiket berhasil diperbarui!');
        }

        abort(403, 'Unauthorized');
    }

    public function addResponse(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'message' => 'required|string',
            'is_internal' => 'nullable|boolean',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['ticket_id'] = $ticket->id;

        // Only technicians and admins can add internal notes
        if (!auth()->user()->isAdmin() && !auth()->user()->isTechnician()) {
            $validated['is_internal'] = false;
        }

        TicketResponse::create($validated);

        $isInternal = $validated['is_internal'] ?? false;

        if ($isInternal) {
            // Notify admins and technicians for internal notes
            $recipients = User::whereIn('role', ['admin', 'general_manager', 'technician'])->get();
            $this->sendTicketNotification(
                $recipients,
                'catatan_internal',
                $ticket,
                'Catatan internal baru',
                sprintf('Catatan internal baru ditambahkan pada tiket %s', $ticket->ticket_number)
            );
        } else {
            // Notify ticket owner and assigned technician
            $recipients = [$ticket->user, $ticket->assignedTo];
            $this->sendTicketNotification(
                $recipients,
                'tanggapan_baru',
                $ticket,
                'Tanggapan baru pada tiket',
                sprintf('Tanggapan baru ditambahkan pada tiket %s', $ticket->ticket_number)
            );
        }

        return back()->with('success', 'Tanggapan berhasil ditambahkan!');
    }

    public function assign(Request $request, Ticket $ticket)
    {
        $user = auth()->user();

        if (!$user->isAdmin() && !$user->isTechnician()) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'assigned_to' => 'required|exists:users,id',
        ]);

        $previousAssigned = $ticket->assigned_to;

        $ticket->update($validated);

        if ($previousAssigned !== $ticket->assigned_to) {
            $assignedUser = User::find($validated['assigned_to']);
            $recipients = [$assignedUser, $ticket->user];
            $this->sendTicketNotification(
                $recipients,
                'tiket_ditugaskan',
                $ticket,
                'Tiket ditugaskan',
                sprintf('Tiket %s ditugaskan kepada %s', $ticket->ticket_number, optional($assignedUser)->name)
            );
        }

        return back()->with('success', 'Tiket berhasil ditugaskan!');
    }

    public function assignToSelf(Ticket $ticket)
    {
        $user = auth()->user();

        if (!$user->isTechnician() && !$user->isAdmin()) {
            abort(403, 'Unauthorized');
        }

        $ticket->update(['assigned_to' => $user->id]);

        $recipients = [$ticket->user];
        $this->sendTicketNotification(
            $recipients,
            'tiket_diambil',
            $ticket,
            'Tiket diambil',
            sprintf('Tiket %s diambil oleh %s', $ticket->ticket_number, $user->name)
        );

        return back()->with('success', 'Tiket berhasil diambil!');
    }

    private function sendTicketNotification($recipients, string $type, Ticket $ticket, string $title, string $message): void
    {
        $users = collect($recipients)
            ->flatten()
            ->filter()
            ->unique('id')
            ->reject(function ($recipient) {
                return $recipient && $recipient->id === auth()->id();
            });

        if ($users->isEmpty()) {
            return;
        }

        Notification::send($users, new TicketActivityNotification([
            'type' => $type,
            'ticket_id' => $ticket->id,
            'ticket_number' => $ticket->ticket_number,
            'title' => $title,
            'message' => $message,
            'url' => route('tickets.show', $ticket),
            'by_user_id' => auth()->id(),
            'by_user_name' => auth()->user()->name,
        ]));
    }
}
