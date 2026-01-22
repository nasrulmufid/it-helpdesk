@extends('layouts.app')

@section('title', 'Dashboard Teknisi')

@section('content')
<div class="dashboard">
    <h1>Dashboard Teknisi</h1>
    
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">📋</div>
            <div class="stat-info">
                <h3>Tiket Saya</h3>
                <p class="stat-number">{{ $stats['assigned_tickets'] }}</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">🆕</div>
            <div class="stat-info">
                <h3>Terbuka</h3>
                <p class="stat-number">{{ $stats['open_tickets'] }}</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">⚙️</div>
            <div class="stat-info">
                <h3>Dalam Proses</h3>
                <p class="stat-number">{{ $stats['in_progress_tickets'] }}</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">✅</div>
            <div class="stat-info">
                <h3>Terselesaikan</h3>
                <p class="stat-number">{{ $stats['resolved_tickets'] }}</p>
            </div>
        </div>
    </div>

    <div class="dashboard-section">
        <h2>Tiket yang Ditugaskan kepada Saya</h2>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>No. Tiket</th>
                        <th>Judul</th>
                        <th>Pengguna</th>
                        <th>Kategori</th>
                        <th>Prioritas</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($myTickets as $ticket)
                    <tr>
                        <td>{{ $ticket->ticket_number }}</td>
                        <td>{{ $ticket->title }}</td>
                        <td>{{ $ticket->user->name }}</td>
                        <td>{{ $ticket->category->icon }} {{ $ticket->category->name }}</td>
                        <td><span class="badge badge-{{ $ticket->priority }}">{{ $ticket->priority_label }}</span></td>
                        <td><span class="badge badge-{{ $ticket->status }}">{{ $ticket->status_label }}</span></td>
                        <td>
                            <a href="{{ route('tickets.show', $ticket) }}" class="btn btn-sm btn-info">Lihat</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">Belum ada tiket yang ditugaskan</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="dashboard-section">
        <h2>Tiket Belum Ditugaskan</h2>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>No. Tiket</th>
                        <th>Judul</th>
                        <th>Pengguna</th>
                        <th>Kategori</th>
                        <th>Prioritas</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($unassignedTickets as $ticket)
                    <tr>
                        <td>{{ $ticket->ticket_number }}</td>
                        <td>{{ $ticket->title }}</td>
                        <td>{{ $ticket->user->name }}</td>
                        <td>{{ $ticket->category->icon }} {{ $ticket->category->name }}</td>
                        <td><span class="badge badge-{{ $ticket->priority }}">{{ $ticket->priority_label }}</span></td>
                        <td>
                            <form action="{{ route('tickets.assignToSelf', $ticket) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success">Ambil Tiket</button>
                            </form>
                            <a href="{{ route('tickets.show', $ticket) }}" class="btn btn-sm btn-info">Lihat</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">Tidak ada tiket yang belum ditugaskan</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
