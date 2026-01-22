@extends('layouts.app')

@section('title', 'Detail Tiket #' . $ticket->ticket_number)

@section('content')
<div class="page-header">
    <h1>Tiket #{{ $ticket->ticket_number }}</h1>
    <div class="ticket-badges">
        <span class="badge badge-{{ $ticket->priority }}">{{ $ticket->priority_label }}</span>
        <span class="badge badge-{{ $ticket->status }}">{{ $ticket->status_label }}</span>
    </div>
</div>

<div class="ticket-detail">
    <div class="ticket-main">
        <div class="ticket-card">
            <h2>{{ $ticket->title }}</h2>
            
            <div class="ticket-meta">
                <div class="meta-item">
                    <strong>Kategori:</strong> {{ $ticket->category->icon }} {{ $ticket->category->name }}
                </div>
                <div class="meta-item">
                    <strong>Dibuat oleh:</strong> {{ $ticket->user->name }}
                </div>
                <div class="meta-item">
                    <strong>Tanggal:</strong> {{ $ticket->created_at->format('d F Y H:i') }}
                </div>
                <div class="meta-item">
                    <strong>Ditugaskan kepada:</strong> {{ $ticket->assignedTo->name ?? 'Belum ditugaskan' }}
                </div>
            </div>

            <div class="ticket-description">
                <h3>Deskripsi</h3>
                <p>{{ $ticket->description }}</p>
            </div>
        </div>

        <!-- Responses -->
        <div class="ticket-responses">
            <h3>Tanggapan</h3>
            
            @forelse($ticket->responses as $response)
            <div class="response-item {{ $response->is_internal ? 'internal' : '' }}">
                <div class="response-header">
                    <strong>{{ $response->user->name }}</strong>
                    <span class="response-date">{{ $response->created_at->format('d/m/Y H:i') }}</span>
                    @if($response->is_internal)
                    <span class="badge badge-warning">Internal</span>
                    @endif
                </div>
                <div class="response-body">
                    {{ $response->message }}
                </div>
            </div>
            @empty
            <p class="text-muted">Belum ada tanggapan</p>
            @endforelse
        </div>

        <!-- Add Response Form -->
        <div class="response-form">
            <h3>Tambah Tanggapan</h3>
            <form method="POST" action="{{ route('tickets.addResponse', $ticket) }}">
                @csrf
                
                <div class="form-group">
                    <textarea name="message" rows="4" placeholder="Tulis tanggapan Anda..." required></textarea>
                </div>

                @if(auth()->user()->isAdmin() || auth()->user()->isTechnician())
                <div class="form-group-checkbox">
                    <input type="checkbox" id="is_internal" name="is_internal" value="1">
                    <label for="is_internal">Catatan internal (tidak terlihat oleh pengguna)</label>
                </div>
                @endif

                <button type="submit" class="btn btn-primary">Kirim Tanggapan</button>
            </form>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="ticket-sidebar">
        @if(auth()->user()->isAdmin() || auth()->user()->isTechnician())
        <!-- Update Status -->
        <div class="sidebar-card">
            <h3>Update Status</h3>
            <form method="POST" action="{{ route('tickets.update', $ticket) }}">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label for="status">Status</label>
                    <select id="status" name="status">
                        <option value="open" {{ $ticket->status == 'open' ? 'selected' : '' }}>Terbuka</option>
                        <option value="in_progress" {{ $ticket->status == 'in_progress' ? 'selected' : '' }}>Dalam Proses</option>
                        <option value="resolved" {{ $ticket->status == 'resolved' ? 'selected' : '' }}>Terselesaikan</option>
                        <option value="closed" {{ $ticket->status == 'closed' ? 'selected' : '' }}>Ditutup</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="priority">Prioritas</label>
                    <select id="priority" name="priority">
                        <option value="low" {{ $ticket->priority == 'low' ? 'selected' : '' }}>Rendah</option>
                        <option value="medium" {{ $ticket->priority == 'medium' ? 'selected' : '' }}>Sedang</option>
                        <option value="high" {{ $ticket->priority == 'high' ? 'selected' : '' }}>Tinggi</option>
                        <option value="critical" {{ $ticket->priority == 'critical' ? 'selected' : '' }}>Kritis</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary btn-block">Update</button>
            </form>
        </div>

        <!-- Assign Technician -->
        @if(auth()->user()->isAdmin())
        <div class="sidebar-card">
            <h3>Tugaskan Teknisi</h3>
            <form method="POST" action="{{ route('tickets.assign', $ticket) }}">
                @csrf
                
                <div class="form-group">
                    <select name="assigned_to">
                        <option value="">-- Pilih Teknisi --</option>
                        @foreach($technicians as $tech)
                        <option value="{{ $tech->id }}" {{ $ticket->assigned_to == $tech->id ? 'selected' : '' }}>
                            {{ $tech->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-success btn-block">Tugaskan</button>
            </form>
        </div>
        @endif

        @if(auth()->user()->isTechnician() && !$ticket->assigned_to)
        <div class="sidebar-card">
            <form method="POST" action="{{ route('tickets.assignToSelf', $ticket) }}">
                @csrf
                <button type="submit" class="btn btn-success btn-block">Ambil Tiket Ini</button>
            </form>
        </div>
        @endif
        @endif

        <!-- Ticket Info -->
        <div class="sidebar-card">
            <h3>Informasi Tiket</h3>
            <div class="info-list">
                <div class="info-item">
                    <span class="info-label">Nomor Tiket:</span>
                    <span class="info-value">{{ $ticket->ticket_number }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Dibuat:</span>
                    <span class="info-value">{{ $ticket->created_at->format('d/m/Y H:i') }}</span>
                </div>
                @if($ticket->resolved_at)
                <div class="info-item">
                    <span class="info-label">Diselesaikan:</span>
                    <span class="info-value">{{ $ticket->resolved_at->format('d/m/Y H:i') }}</span>
                </div>
                @endif
                @if($ticket->closed_at)
                <div class="info-item">
                    <span class="info-label">Ditutup:</span>
                    <span class="info-value">{{ $ticket->closed_at->format('d/m/Y H:i') }}</span>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="back-link">
    <a href="{{ route('tickets.index') }}" class="btn btn-secondary">← Kembali ke Daftar Tiket</a>
</div>
@endsection
