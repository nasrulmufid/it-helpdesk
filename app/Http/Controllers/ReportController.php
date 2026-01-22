<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Category;
use App\Models\TicketResponse;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index()
    {
        // Overview statistics
        $totalTickets = Ticket::count();
        $totalUsers = User::where('role', 'user')->count();
        $totalTechnicians = User::where('role', 'technician')->count();
        $avgResponseTime = $this->getAverageResponseTime();

        return view('reports.index', compact(
            'totalTickets',
            'totalUsers',
            'totalTechnicians',
            'avgResponseTime'
        ));
    }

    public function ticketStatus(Request $request)
    {
        $startDate = $request->input('start_date', now()->subMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));

        $tickets = Ticket::with(['user', 'category', 'assignedTo'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        $statusCounts = [
            'open' => $tickets->where('status', 'open')->count(),
            'in_progress' => $tickets->where('status', 'in_progress')->count(),
            'resolved' => $tickets->where('status', 'resolved')->count(),
            'closed' => $tickets->where('status', 'closed')->count(),
        ];

        $priorityCounts = [
            'low' => $tickets->where('priority', 'low')->count(),
            'medium' => $tickets->where('priority', 'medium')->count(),
            'high' => $tickets->where('priority', 'high')->count(),
            'critical' => $tickets->where('priority', 'critical')->count(),
        ];

        return view('reports.ticket-status', compact(
            'tickets',
            'statusCounts',
            'priorityCounts',
            'startDate',
            'endDate'
        ));
    }

    public function categoryPerformance(Request $request)
    {
        $startDate = $request->input('start_date', now()->subMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));

        $categories = Category::withCount([
            'tickets' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }
        ])->get();

        $categoryStats = $categories->map(function ($category) use ($startDate, $endDate) {
            $tickets = $category->tickets()
                ->whereBetween('created_at', [$startDate, $endDate])
                ->get();

            return [
                'category' => $category,
                'total' => $tickets->count(),
                'open' => $tickets->where('status', 'open')->count(),
                'in_progress' => $tickets->where('status', 'in_progress')->count(),
                'resolved' => $tickets->where('status', 'resolved')->count(),
                'closed' => $tickets->where('status', 'closed')->count(),
                'avg_resolution_time' => $this->calculateAvgResolutionTime($tickets),
            ];
        });

        return view('reports.category-performance', compact(
            'categoryStats',
            'startDate',
            'endDate'
        ));
    }

    public function technicianPerformance(Request $request)
    {
        $startDate = $request->input('start_date', now()->subMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));

        $technicians = User::where('role', 'technician')
            ->withCount([
                'assignedTickets' => function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('created_at', [$startDate, $endDate]);
                }
            ])
            ->get();

        $technicianStats = $technicians->map(function ($technician) use ($startDate, $endDate) {
            $tickets = $technician->assignedTickets()
                ->whereBetween('created_at', [$startDate, $endDate])
                ->get();

            return [
                'technician' => $technician,
                'total_assigned' => $tickets->count(),
                'resolved' => $tickets->where('status', 'resolved')->count(),
                'in_progress' => $tickets->where('status', 'in_progress')->count(),
                'avg_resolution_time' => $this->calculateAvgResolutionTime($tickets),
                'response_count' => TicketResponse::where('user_id', $technician->id)
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->count(),
            ];
        });

        return view('reports.technician-performance', compact(
            'technicianStats',
            'startDate',
            'endDate'
        ));
    }

    public function userActivity(Request $request)
    {
        $startDate = $request->input('start_date', now()->subMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));

        $users = User::where('role', 'user')
            ->withCount([
                'tickets' => function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('created_at', [$startDate, $endDate]);
                }
            ])
            ->having('tickets_count', '>', 0)
            ->orderBy('tickets_count', 'desc')
            ->get();

        $userStats = $users->map(function ($user) use ($startDate, $endDate) {
            $tickets = $user->tickets()
                ->whereBetween('created_at', [$startDate, $endDate])
                ->get();

            return [
                'user' => $user,
                'total_tickets' => $tickets->count(),
                'open' => $tickets->where('status', 'open')->count(),
                'resolved' => $tickets->where('status', 'resolved')->count(),
                'closed' => $tickets->where('status', 'closed')->count(),
            ];
        });

        return view('reports.user-activity', compact(
            'userStats',
            'startDate',
            'endDate'
        ));
    }

    private function getAverageResponseTime()
    {
        $tickets = Ticket::whereNotNull('assigned_to')
            ->with('responses')
            ->get();

        $totalTime = 0;
        $count = 0;

        foreach ($tickets as $ticket) {
            $firstResponse = $ticket->responses->first();
            if ($firstResponse) {
                $timeDiff = $ticket->created_at->diffInMinutes($firstResponse->created_at);
                $totalTime += $timeDiff;
                $count++;
            }
        }

        return $count > 0 ? round($totalTime / $count) : 0;
    }

    private function calculateAvgResolutionTime($tickets)
    {
        $resolvedTickets = $tickets->whereIn('status', ['resolved', 'closed']);

        if ($resolvedTickets->isEmpty()) {
            return 0;
        }

        $totalTime = 0;
        foreach ($resolvedTickets as $ticket) {
            $totalTime += $ticket->created_at->diffInHours($ticket->updated_at);
        }

        return round($totalTime / $resolvedTickets->count(), 1);
    }

    public function exportPdf(Request $request)
    {
        $reportType = $request->input('type', 'complete');
        $startDate = $request->input('start_date', now()->subMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));

        // Get all statistics
        $totalTickets = Ticket::count();
        $totalUsers = User::where('role', 'user')->count();
        $totalTechnicians = User::where('role', 'technician')->count();
        $avgResponseTime = $this->getAverageResponseTime();

        // Ticket Status Data
        $tickets = Ticket::with(['user', 'category', 'assignedTo'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        $statusCounts = [
            'open' => $tickets->where('status', 'open')->count(),
            'in_progress' => $tickets->where('status', 'in_progress')->count(),
            'resolved' => $tickets->where('status', 'resolved')->count(),
            'closed' => $tickets->where('status', 'closed')->count(),
        ];

        $priorityCounts = [
            'low' => $tickets->where('priority', 'low')->count(),
            'medium' => $tickets->where('priority', 'medium')->count(),
            'high' => $tickets->where('priority', 'high')->count(),
            'critical' => $tickets->where('priority', 'critical')->count(),
        ];

        // Category Performance Data
        $categories = Category::all();
        $categoryStats = $categories->map(function ($category) use ($startDate, $endDate) {
            $tickets = $category->tickets()
                ->whereBetween('created_at', [$startDate, $endDate])
                ->get();

            return [
                'category' => $category,
                'total' => $tickets->count(),
                'open' => $tickets->where('status', 'open')->count(),
                'in_progress' => $tickets->where('status', 'in_progress')->count(),
                'resolved' => $tickets->where('status', 'resolved')->count(),
                'closed' => $tickets->where('status', 'closed')->count(),
                'avg_resolution_time' => $this->calculateAvgResolutionTime($tickets),
            ];
        });

        // Technician Performance Data
        $technicians = User::where('role', 'technician')->get();
        $technicianStats = $technicians->map(function ($technician) use ($startDate, $endDate) {
            $tickets = $technician->assignedTickets()
                ->whereBetween('created_at', [$startDate, $endDate])
                ->get();

            return [
                'technician' => $technician,
                'total_assigned' => $tickets->count(),
                'resolved' => $tickets->where('status', 'resolved')->count(),
                'in_progress' => $tickets->where('status', 'in_progress')->count(),
                'avg_resolution_time' => $this->calculateAvgResolutionTime($tickets),
                'response_count' => TicketResponse::where('user_id', $technician->id)
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->count(),
            ];
        });

        // User Activity Data
        $users = User::where('role', 'user')
            ->withCount([
                'tickets' => function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('created_at', [$startDate, $endDate]);
                }
            ])
            ->having('tickets_count', '>', 0)
            ->orderBy('tickets_count', 'desc')
            ->get();

        $userStats = $users->map(function ($user) use ($startDate, $endDate) {
            $tickets = $user->tickets()
                ->whereBetween('created_at', [$startDate, $endDate])
                ->get();

            return [
                'user' => $user,
                'total_tickets' => $tickets->count(),
                'open' => $tickets->where('status', 'open')->count(),
                'resolved' => $tickets->where('status', 'resolved')->count(),
                'closed' => $tickets->where('status', 'closed')->count(),
            ];
        });

        $pdf = Pdf::loadView('reports.pdf.complete', compact(
            'totalTickets',
            'totalUsers',
            'totalTechnicians',
            'avgResponseTime',
            'tickets',
            'statusCounts',
            'priorityCounts',
            'categoryStats',
            'technicianStats',
            'userStats',
            'startDate',
            'endDate',
            'reportType'
        ));

        $pdf->setPaper('A4', 'landscape');

        $filename = 'laporan-helpdesk-' . now()->format('Y-m-d-His') . '.pdf';

        return $pdf->download($filename);
    }
}
