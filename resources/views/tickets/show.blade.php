@extends('layouts.app')

@section('title', 'Detail Tiket #' . $ticket->ticket_number)

@section('content')
<!-- Header -->
<div class="mb-6">
    <a href="{{ route('tickets.index') }}" class="inline-flex items-center text-gray-400 hover:text-white mb-4 transition">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Kembali ke Daftar Tiket
    </a>
    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white mb-2">Tiket #{{ $ticket->ticket_number }}</h1>
            <div class="flex items-center gap-2">
                @if($ticket->priority === 'low')
                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-900 text-green-300">{{ $ticket->priority_label }}</span>
                @elseif($ticket->priority === 'medium')
                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-900 text-yellow-300">{{ $ticket->priority_label }}</span>
                @elseif($ticket->priority === 'high')
                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-orange-900 text-orange-300">{{ $ticket->priority_label }}</span>
                @else
                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-900 text-red-300">{{ $ticket->priority_label }}</span>
                @endif
                
                @if($ticket->status === 'open')
                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-cyan-900 text-cyan-300">{{ $ticket->status_label }}</span>
                @elseif($ticket->status === 'in_progress')
                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-900 text-yellow-300">{{ $ticket->status_label }}</span>
                @elseif($ticket->status === 'resolved')
                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-900 text-green-300">{{ $ticket->status_label }}</span>
                @else
                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-700 text-gray-300">{{ $ticket->status_label }}</span>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Main Content Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Ticket Details -->
        <div class="bg-gray-800 rounded-xl border border-gray-700 p-6">
            <h2 class="text-xl font-bold text-white mb-4">{{ $ticket->title }}</h2>
            
            <!-- Meta Info -->
            <div class="grid grid-cols-2 gap-4 mb-6 p-4 bg-gray-700 rounded-lg">
                <div>
                    <p class="text-sm text-gray-400">Kategori</p>
                    <p class="text-white font-medium">{{ $ticket->category->icon }} {{ $ticket->category->name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-400">Dibuat oleh</p>
                    <div class="flex items-center mt-1">
                        <div class="w-6 h-6 bg-gradient-to-br from-purple-500 to-pink-500 rounded-full flex items-center justify-center mr-2">
                            <span class="text-white text-xs font-semibold">{{ strtoupper(substr($ticket->user->name, 0, 2)) }}</span>
                        </div>
                        <p class="text-white font-medium">{{ $ticket->user->name }}</p>
                    </div>
                </div>
                <div>
                    <p class="text-sm text-gray-400">Tanggal</p>
                    <p class="text-white font-medium">{{ $ticket->created_at->format('d F Y H:i') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-400">Ditugaskan kepada</p>
                    @if($ticket->assignedTo)
                        <div class="flex items-center mt-1">
                            <div class="w-6 h-6 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center mr-2">
                                <span class="text-white text-xs font-semibold">{{ strtoupper(substr($ticket->assignedTo->name, 0, 1)) }}</span>
                            </div>
                            <p class="text-white font-medium">{{ $ticket->assignedTo->name }}</p>
                        </div>
                    @else
                        <p class="text-gray-500 italic">Belum ditugaskan</p>
                    @endif
                </div>
            </div>

            <!-- Description -->
            <div class="border-t border-gray-700 pt-6">
                <h3 class="text-lg font-semibold text-white mb-3">Deskripsi</h3>
                <p class="text-gray-300 leading-relaxed whitespace-pre-wrap">{{ $ticket->description }}</p>
            </div>
        </div>

        <!-- Attachments -->
        <div class="bg-gray-800 rounded-xl border border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-white mb-4">Lampiran ({{ $ticket->attachments->count() }})</h3>

            @if($ticket->attachments->isEmpty())
                <div class="text-center py-8 text-gray-400">
                    <p>Tidak ada lampiran</p>
                </div>
            @else
                <div class="space-y-3">
                    @foreach($ticket->attachments as $attachment)
                        @php
                            $isImage = $attachment->file_type && str_starts_with($attachment->file_type, 'image/');
                            $isPdf = $attachment->file_type === 'application/pdf';
                        @endphp
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-gray-700 rounded-lg p-4">
                            <div class="min-w-0">
                                <p class="text-white font-medium truncate">{{ $attachment->file_name }}</p>
                                <p class="text-sm text-gray-400">
                                    {{ $attachment->file_size_formatted }}
                                    @if($attachment->file_type)
                                        • {{ $attachment->file_type }}
                                    @endif
                                </p>
                            </div>
                            <div class="flex gap-2 shrink-0">
                                @if($isImage || $isPdf)
                                    <a
                                        href="{{ route('tickets.attachments.view', [$ticket, $attachment]) }}"
                                        target="_blank"
                                        rel="noopener"
                                        class="px-4 py-2 bg-gray-600 hover:bg-gray-500 text-white text-sm font-semibold rounded-lg transition-all"
                                    >
                                        Lihat
                                    </a>
                                @endif
                                <a
                                    href="{{ route('tickets.attachments.download', [$ticket, $attachment]) }}"
                                    class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-semibold rounded-lg transition-all"
                                >
                                    Download
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Responses -->
        <div class="bg-gray-800 rounded-xl border border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-white mb-4">Tanggapan ({{ $ticket->responses->count() }})</h3>
            
            <div class="space-y-4 mb-6">
                @forelse($ticket->responses as $response)
                <div class="bg-gray-700 rounded-lg p-4 {{ $response->is_internal ? 'border-l-4 border-yellow-500' : 'border-l-4 border-purple-500' }}">
                    <div class="flex items-start justify-between mb-2">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-500 rounded-full flex items-center justify-center mr-3">
                                <span class="text-white text-sm font-semibold">{{ strtoupper(substr($response->user->name, 0, 2)) }}</span>
                            </div>
                            <div>
                                <p class="text-white font-medium">{{ $response->user->name }}</p>
                                <p class="text-sm text-gray-400">{{ $response->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                        @if($response->is_internal)
                        <span class="px-2 py-1 text-xs font-semibold rounded bg-yellow-900 text-yellow-300">Internal</span>
                        @endif
                    </div>
                    <p class="text-gray-300 ml-13 leading-relaxed">{{ $response->message }}</p>
                </div>
                @empty
                <div class="text-center py-8 text-gray-400">
                    <svg class="mx-auto h-12 w-12 text-gray-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    <p>Belum ada tanggapan</p>
                </div>
                @endforelse
            </div>

            <!-- Add Response Form -->
            <div class="border-t border-gray-700 pt-6">
                <h4 class="text-md font-semibold text-white mb-4">Tambah Tanggapan</h4>
                <form method="POST" action="{{ route('tickets.addResponse', $ticket) }}">
                    @csrf
                    
                    <div class="mb-4">
                        <textarea 
                            name="message" 
                            rows="4"
                            class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition resize-none"
                            placeholder="Tulis tanggapan Anda..."
                            required
                        ></textarea>
                    </div>

                    @if(auth()->user()->isAdmin() || auth()->user()->isTechnician())
                    <div class="flex items-center mb-4">
                        <input 
                            type="checkbox" 
                            id="is_internal" 
                            name="is_internal" 
                            value="1"
                            class="w-4 h-4 bg-gray-700 border-gray-600 rounded text-purple-500 focus:ring-2 focus:ring-purple-500"
                        >
                        <label for="is_internal" class="ml-2 text-sm text-gray-300">
                            Catatan internal (tidak terlihat oleh pengguna)
                        </label>
                    </div>
                    @endif

                    <button 
                        type="submit"
                        class="px-6 py-3 bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white font-semibold rounded-lg transition-all transform hover:scale-105"
                    >
                        Kirim Tanggapan
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="lg:col-span-1 space-y-6">
        @if(auth()->user()->isAdmin() || auth()->user()->isTechnician())
        <!-- Update Status -->
        <div class="bg-gray-800 rounded-xl border border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-white mb-4">Update Status</h3>
            <form method="POST" action="{{ route('tickets.update', $ticket) }}">
                @csrf
                @method('PUT')
                
                <div class="mb-4">
                    <label for="status" class="block text-sm font-medium text-gray-300 mb-2">Status</label>
                    <select 
                        id="status" 
                        name="status"
                        class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                    >
                        <option value="open" {{ $ticket->status == 'open' ? 'selected' : '' }}>Terbuka</option>
                        <option value="in_progress" {{ $ticket->status == 'in_progress' ? 'selected' : '' }}>Dalam Proses</option>
                        <option value="resolved" {{ $ticket->status == 'resolved' ? 'selected' : '' }}>Terselesaikan</option>
                        <option value="closed" {{ $ticket->status == 'closed' ? 'selected' : '' }}>Ditutup</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="priority" class="block text-sm font-medium text-gray-300 mb-2">Prioritas</label>
                    <select 
                        id="priority" 
                        name="priority"
                        class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                    >
                        <option value="low" {{ $ticket->priority == 'low' ? 'selected' : '' }}>Rendah</option>
                        <option value="medium" {{ $ticket->priority == 'medium' ? 'selected' : '' }}>Sedang</option>
                        <option value="high" {{ $ticket->priority == 'high' ? 'selected' : '' }}>Tinggi</option>
                        <option value="critical" {{ $ticket->priority == 'critical' ? 'selected' : '' }}>Kritis</option>
                    </select>
                </div>

                <button 
                    type="submit"
                    class="w-full px-4 py-3 bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white font-semibold rounded-lg transition-all"
                >
                    Update
                </button>
            </form>
        </div>

        <!-- Assign Technician -->
        @if(auth()->user()->isAdmin())
        <div class="bg-gray-800 rounded-xl border border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-white mb-4">Tugaskan Teknisi</h3>
            <form method="POST" action="{{ route('tickets.assign', $ticket) }}">
                @csrf
                
                <div class="mb-4">
                    <select 
                        name="assigned_to"
                        class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                    >
                        <option value="">-- Pilih Teknisi --</option>
                        @foreach($technicians as $tech)
                        <option value="{{ $tech->id }}" {{ $ticket->assigned_to == $tech->id ? 'selected' : '' }}>
                            {{ $tech->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <button 
                    type="submit"
                    class="w-full px-4 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition-all"
                >
                    Tugaskan
                </button>
            </form>
        </div>
        @endif

        @if(auth()->user()->isTechnician() && !$ticket->assigned_to)
        <div class="bg-gray-800 rounded-xl border border-gray-700 p-6">
            <form method="POST" action="{{ route('tickets.assignToSelf', $ticket) }}">
                @csrf
                <button 
                    type="submit"
                    class="w-full px-4 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition-all"
                >
                    Ambil Tiket Ini
                </button>
            </form>
        </div>
        @endif
        @endif

        <!-- Ticket Info -->
        <div class="bg-gray-800 rounded-xl border border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-white mb-4">Informasi Tiket</h3>
            <div class="space-y-3">
                <div class="flex justify-between py-2 border-b border-gray-700">
                    <span class="text-gray-400">Nomor Tiket</span>
                    <span class="text-white font-medium">{{ $ticket->ticket_number }}</span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-700">
                    <span class="text-gray-400">Dibuat</span>
                    <span class="text-white font-medium">{{ $ticket->created_at->format('d/m/Y H:i') }}</span>
                </div>
                @if($ticket->resolved_at)
                <div class="flex justify-between py-2 border-b border-gray-700">
                    <span class="text-gray-400">Diselesaikan</span>
                    <span class="text-white font-medium">{{ $ticket->resolved_at->format('d/m/Y H:i') }}</span>
                </div>
                @endif
                @if($ticket->closed_at)
                <div class="flex justify-between py-2 border-b border-gray-700">
                    <span class="text-gray-400">Ditutup</span>
                    <span class="text-white font-medium">{{ $ticket->closed_at->format('d/m/Y H:i') }}</span>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
