@extends('layouts.app')

@section('title', 'Laporan Status Tiket')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <div class="flex items-center gap-3">
                <a href="{{ route('reports.index') }}" class="text-gray-400 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <h2 class="text-2xl font-bold text-white">Laporan Status Tiket</h2>
            </div>
            <p class="text-gray-400 mt-1 ml-9">Distribusi tiket berdasarkan status dan prioritas</p>
        </div>
        <a href="{{ route('reports.export-pdf', ['type' => 'ticket-status', 'start_date' => $startDate, 'end_date' => $endDate]) }}" 
           class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            Export PDF
        </a>
    </div>

    <!-- Date Filter -->
    <div class="bg-gray-800 rounded-xl p-6 border border-gray-700">
        <form action="{{ route('reports.ticket-status') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
            <div class="flex-1">
                <label for="start_date" class="block text-sm font-medium text-gray-300 mb-2">Tanggal Mulai</label>
                <input type="date" name="start_date" id="start_date" value="{{ $startDate }}"
                    class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div class="flex-1">
                <label for="end_date" class="block text-sm font-medium text-gray-300 mb-2">Tanggal Akhir</label>
                <input type="date" name="end_date" id="end_date" value="{{ $endDate }}"
                    class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                Filter
            </button>
        </form>
    </div>

    <!-- Status Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-gray-800 rounded-xl p-6 border border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400">Open</p>
                    <p class="text-3xl font-bold text-blue-400">{{ $statusCounts['open'] }}</p>
                </div>
                <div class="p-3 bg-blue-500/20 rounded-lg">
                    <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gray-800 rounded-xl p-6 border border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400">Dalam Proses</p>
                    <p class="text-3xl font-bold text-yellow-400">{{ $statusCounts['in_progress'] }}</p>
                </div>
                <div class="p-3 bg-yellow-500/20 rounded-lg">
                    <svg class="w-8 h-8 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gray-800 rounded-xl p-6 border border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400">Terselesaikan</p>
                    <p class="text-3xl font-bold text-green-400">{{ $statusCounts['resolved'] }}</p>
                </div>
                <div class="p-3 bg-green-500/20 rounded-lg">
                    <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gray-800 rounded-xl p-6 border border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400">Ditutup</p>
                    <p class="text-3xl font-bold text-gray-400">{{ $statusCounts['closed'] }}</p>
                </div>
                <div class="p-3 bg-gray-500/20 rounded-lg">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="M5 13l4 4L19 7" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Priority Cards -->
    <div class="bg-gray-800 rounded-xl border border-gray-700">
        <div class="p-6 border-b border-gray-700">
            <h3 class="text-lg font-semibold text-white">Distribusi Prioritas</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div class="text-center p-4 bg-gray-700/50 rounded-lg">
                    <p class="text-3xl font-bold text-green-400">{{ $priorityCounts['low'] }}</p>
                    <p class="text-sm text-gray-400 mt-1">Low</p>
                </div>
                <div class="text-center p-4 bg-gray-700/50 rounded-lg">
                    <p class="text-3xl font-bold text-yellow-400">{{ $priorityCounts['medium'] }}</p>
                    <p class="text-sm text-gray-400 mt-1">Medium</p>
                </div>
                <div class="text-center p-4 bg-gray-700/50 rounded-lg">
                    <p class="text-3xl font-bold text-orange-400">{{ $priorityCounts['high'] }}</p>
                    <p class="text-sm text-gray-400 mt-1">High</p>
                </div>
                <div class="text-center p-4 bg-gray-700/50 rounded-lg">
                    <p class="text-3xl font-bold text-red-400">{{ $priorityCounts['critical'] }}</p>
                    <p class="text-sm text-gray-400 mt-1">Critical</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Ticket Table -->
    <div class="bg-gray-800 rounded-xl border border-gray-700">
        <div class="p-6 border-b border-gray-700">
            <h3 class="text-lg font-semibold text-white">Daftar Tiket ({{ $tickets->count() }} tiket)</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Judul</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Pengguna</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Prioritas</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    @forelse($tickets as $ticket)
                        <tr class="hover:bg-gray-700/50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">#{{ $ticket->id }}</td>
                            <td class="px-6 py-4 text-sm text-white">
                                <a href="{{ route('tickets.show', $ticket) }}" class="hover:text-blue-400">
                                    {{ Str::limit($ticket->title, 40) }}
                                </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $ticket->user->name ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $ticket->category->name ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusColors = [
                                        'open' => 'bg-blue-500/20 text-blue-400',
                                        'in_progress' => 'bg-yellow-500/20 text-yellow-400',
                                        'resolved' => 'bg-green-500/20 text-green-400',
                                        'closed' => 'bg-gray-500/20 text-gray-400',
                                    ];
                                @endphp
                                <span class="px-2 py-1 text-xs font-medium rounded-full {{ $statusColors[$ticket->status] ?? 'bg-gray-500/20 text-gray-400' }}">
                                    {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $priorityColors = [
                                        'low' => 'bg-green-500/20 text-green-400',
                                        'medium' => 'bg-yellow-500/20 text-yellow-400',
                                        'high' => 'bg-orange-500/20 text-orange-400',
                                        'critical' => 'bg-red-500/20 text-red-400',
                                    ];
                                @endphp
                                <span class="px-2 py-1 text-xs font-medium rounded-full {{ $priorityColors[$ticket->priority] ?? 'bg-gray-500/20 text-gray-400' }}">
                                    {{ ucfirst($ticket->priority) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $ticket->created_at->format('d M Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-400">
                                Tidak ada tiket dalam periode ini
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
