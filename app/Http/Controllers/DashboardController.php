<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (!$user instanceof User) {
            abort(403, 'Unauthorized');
        }

        if ($user->isAdmin()) {
            return $this->adminDashboard();
        } elseif ($user->isGeneralManager()) {
            return $this->adminDashboard(); // General Manager uses same dashboard as Admin
        } elseif ($user->isTechnician()) {
            return $this->technicianDashboard();
        } else {
            return $this->userDashboard();
        }
    }

    private function adminDashboard()
    {
        $stats = [
            'total_tickets' => Ticket::count(),
            'open_tickets' => Ticket::open()->count(),
            'in_progress_tickets' => Ticket::inProgress()->count(),
            'resolved_tickets' => Ticket::resolved()->count(),
            'closed_tickets' => Ticket::closed()->count(),
            'critical_tickets' => Ticket::where('priority', 'critical')->count(),
            'total_users' => User::where('role', 'user')->count(),
            'total_technicians' => User::where('role', 'technician')->count(),
        ];

        $recentTickets = Ticket::with(['user', 'category', 'assignedTo'])
            ->latest()
            ->take(10)
            ->get();

        $ticketsByCategory = Category::withCount('tickets')->get();

        return view('dashboard.admin', compact('stats', 'recentTickets', 'ticketsByCategory'));
    }

    private function technicianDashboard()
    {
        $user = Auth::user();
        if (!$user instanceof User) {
            abort(403, 'Unauthorized');
        }

        $stats = [
            'assigned_tickets' => Ticket::where('assigned_to', $user->id)->count(),
            'open_tickets' => Ticket::where('assigned_to', $user->id)->open()->count(),
            'in_progress_tickets' => Ticket::where('assigned_to', $user->id)->inProgress()->count(),
            'resolved_tickets' => Ticket::where('assigned_to', $user->id)->resolved()->count(),
        ];

        $assignedTickets = Ticket::with(['user', 'category'])
            ->where('assigned_to', $user->id)
            ->latest()
            ->take(10)
            ->get();

        $unassignedTickets = Ticket::with(['user', 'category'])
            ->whereNull('assigned_to')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.technician', compact('stats', 'assignedTickets', 'unassignedTickets'));
    }

    private function userDashboard()
    {
        $user = Auth::user();
        if (!$user instanceof User) {
            abort(403, 'Unauthorized');
        }

        $stats = [
            'total_tickets' => Ticket::where('user_id', $user->id)->count(),
            'open_tickets' => Ticket::where('user_id', $user->id)->open()->count(),
            'in_progress_tickets' => Ticket::where('user_id', $user->id)->inProgress()->count(),
            'resolved_tickets' => Ticket::where('user_id', $user->id)->resolved()->count(),
        ];

        $tickets = Ticket::with(['category', 'assignedTo'])
            ->where('user_id', $user->id)
            ->latest()
            ->take(10)
            ->get();

        $categories = Category::active()->get();

        return view('dashboard.user', compact('stats', 'tickets', 'categories'));
    }
}
