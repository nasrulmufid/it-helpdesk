@extends('layouts.app')

@section('title', 'Dashboard Pengguna')

@section('content')
<div class="dashboard">
    <h1>Dashboard Pengguna</h1>
    
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">📊</div>
            <div class="stat-info">
                <h3>Total Tiket Saya</h3>
                <p class="stat-number">{{ $stats['total_tickets'] }}</p>
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

    <div class="action-buttons">
        <a href="{{ route('tickets.create') }}" class="btn btn-primary">➕ Buat Tiket Baru</a>
        <a href="{{ route('tickets.index') }}" class="btn btn-secondary">📋 Lihat Semua Tiket</a>
    </div>

    <div class="dashboard-section">
        <h2>Tiket Saya</h2>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>No. Tiket</th>
                        <th>Judul</th>
                        <th>Kategori</th>
                        <th>Prioritas</th>
                        <th>Status</th>
                        <th>Ditugaskan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($myTickets as $ticket)
                    <tr>
                        <td>{{ $ticket->ticket_number }}</td>
                        <td>{{ $ticket->title }}</td>
                        <td>{{ $ticket->category->icon }} {{ $ticket->category->name }}</td>
                        <td><span class="badge badge-{{ $ticket->priority }}">{{ $ticket->priority_label }}</span></td>
                        <td><span class="badge badge-{{ $ticket->status }}">{{ $ticket->status_label }}</span></td>
                        <td>{{ $ticket->assignedTo->name ?? 'Belum ditugaskan' }}</td>
                        <td>
                            <a href="{{ route('tickets.show', $ticket) }}" class="btn btn-sm btn-info">Lihat</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">Belum ada tiket. <a href="{{ route('tickets.create') }}">Buat tiket baru</a></td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="dashboard-section">
        <h2>Kategori yang Tersedia</h2>
        <div class="category-grid">
            @foreach($categories as $category)
            <div class="category-card">
                <div class="category-icon-large">{{ $category->icon }}</div>
                <h3>{{ $category->name }}</h3>
                <p>{{ $category->description }}</p>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
