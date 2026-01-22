@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Tiket -->
        <a href="{{ route('tickets.index') }}"
            class="bg-gray-800 rounded-xl p-6 border border-gray-700 hover:border-purple-500 transition-all duration-300 transform hover:scale-105 cursor-pointer group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm font-medium group-hover:text-purple-400 transition-colors">Total Tiket
                    </p>
                    <h3 class="text-3xl font-bold text-white mt-2">{{ $stats['total_tickets'] }}</h3>
                </div>
                <div
                    class="bg-gradient-to-br from-blue-500 to-blue-600 p-3 rounded-lg group-hover:shadow-lg group-hover:shadow-blue-500/50 transition-all">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                        </path>
                    </svg>
                </div>
            </div>
        </a>

        <!-- Tiket Terbuka -->
        <a href="{{ route('tickets.index', ['status' => 'open']) }}"
            class="bg-gray-800 rounded-xl p-6 border border-gray-700 hover:border-cyan-500 transition-all duration-300 transform hover:scale-105 cursor-pointer group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm font-medium group-hover:text-cyan-400 transition-colors">Tiket Terbuka
                    </p>
                    <h3 class="text-3xl font-bold text-white mt-2">{{ $stats['open_tickets'] }}</h3>
                </div>
                <div
                    class="bg-gradient-to-br from-cyan-500 to-cyan-600 p-3 rounded-lg group-hover:shadow-lg group-hover:shadow-cyan-500/50 transition-all">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </a>

        <!-- Dalam Proses -->
        <a href="{{ route('tickets.index', ['status' => 'in_progress']) }}"
            class="bg-gray-800 rounded-xl p-6 border border-gray-700 hover:border-yellow-500 transition-all duration-300 transform hover:scale-105 cursor-pointer group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm font-medium group-hover:text-yellow-400 transition-colors">Dalam Proses
                    </p>
                    <h3 class="text-3xl font-bold text-white mt-2">{{ $stats['in_progress_tickets'] }}</h3>
                </div>
                <div
                    class="bg-gradient-to-br from-yellow-500 to-yellow-600 p-3 rounded-lg group-hover:shadow-lg group-hover:shadow-yellow-500/50 transition-all">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                        </path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
            </div>
        </a>

        <!-- Terselesaikan -->
        <a href="{{ route('tickets.index', ['status' => 'resolved']) }}"
            class="bg-gray-800 rounded-xl p-6 border border-gray-700 hover:border-green-500 transition-all duration-300 transform hover:scale-105 cursor-pointer group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm font-medium group-hover:text-green-400 transition-colors">Terselesaikan
                    </p>
                    <h3 class="text-3xl font-bold text-white mt-2">{{ $stats['resolved_tickets'] }}</h3>
                </div>
                <div
                    class="bg-gradient-to-br from-green-500 to-green-600 p-3 rounded-lg group-hover:shadow-lg group-hover:shadow-green-500/50 transition-all">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </a>

        <!-- Tiket Kritis -->
        <a href="{{ route('tickets.index', ['priority' => 'critical']) }}"
            class="bg-gray-800 rounded-xl p-6 border border-gray-700 hover:border-red-500 transition-all duration-300 transform hover:scale-105 cursor-pointer group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm font-medium group-hover:text-red-400 transition-colors">Tiket Kritis</p>
                    <h3 class="text-3xl font-bold text-white mt-2">{{ $stats['critical_tickets'] }}</h3>
                </div>
                <div
                    class="bg-gradient-to-br from-red-500 to-red-600 p-3 rounded-lg group-hover:shadow-lg group-hover:shadow-red-500/50 transition-all">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                        </path>
                    </svg>
                </div>
            </div>
        </a>

        <!-- Total Pengguna -->
        <a href="{{ route('admin.users') }}"
            class="bg-gray-800 rounded-xl p-6 border border-gray-700 hover:border-purple-500 transition-all duration-300 transform hover:scale-105 cursor-pointer group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm font-medium group-hover:text-purple-400 transition-colors">Total
                        Pengguna</p>
                    <h3 class="text-3xl font-bold text-white mt-2">{{ $stats['total_users'] }}</h3>
                </div>
                <div
                    class="bg-gradient-to-br from-purple-500 to-purple-600 p-3 rounded-lg group-hover:shadow-lg group-hover:shadow-purple-500/50 transition-all">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                        </path>
                    </svg>
                </div>
            </div>
        </a>

        <!-- Teknisi -->
        <a href="{{ route('admin.users') }}"
            class="bg-gray-800 rounded-xl p-6 border border-gray-700 hover:border-indigo-500 transition-all duration-300 transform hover:scale-105 cursor-pointer group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm font-medium group-hover:text-indigo-400 transition-colors">Teknisi</p>
                    <h3 class="text-3xl font-bold text-white mt-2">{{ $stats['total_technicians'] }}</h3>
                </div>
                <div
                    class="bg-gradient-to-br from-indigo-500 to-indigo-600 p-3 rounded-lg group-hover:shadow-lg group-hover:shadow-indigo-500/50 transition-all">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                        </path>
                    </svg>
                </div>
            </div>
        </a>

        <!-- Tiket Ditutup -->
        <a href="{{ route('tickets.index', ['status' => 'closed']) }}"
            class="bg-gray-800 rounded-xl p-6 border border-gray-700 hover:border-gray-500 transition-all duration-300 transform hover:scale-105 cursor-pointer group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm font-medium group-hover:text-gray-300 transition-colors">Tiket Ditutup
                    </p>
                    <h3 class="text-3xl font-bold text-white mt-2">{{ $stats['closed_tickets'] }}</h3>
                </div>
                <div class="bg-gradient-to-br from-gray-600 to-gray-700 p-3 rounded-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                        </path>
                    </svg>
                </div>
            </div>
            </a>
    </div>

    <!-- Recent Tickets -->
    <div class="bg-gray-800 rounded-xl border border-gray-700 overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-700">
            <h2 class="text-xl font-bold text-white">Tiket Terbaru</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">No. Tiket
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Judul
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Pengguna
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Kategori
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Prioritas
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                            Ditugaskan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    @forelse($recentTickets as $ticket)
                        <tr class="hover:bg-gray-700 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-medium text-purple-400">{{ $ticket->ticket_number }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-white font-medium">{{ $ticket->title }}</p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-300">{{ $ticket->user->name }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-300">{{ $ticket->category->icon }}
                                    {{ $ticket->category->name }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($ticket->priority === 'low')
                                    <span
                                        class="px-3 py-1 text-xs font-semibold rounded-full bg-green-900 text-green-300">{{ $ticket->priority_label }}</span>
                                @elseif($ticket->priority === 'medium')
                                    <span
                                        class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-900 text-yellow-300">{{ $ticket->priority_label }}</span>
                                @elseif($ticket->priority === 'high')
                                    <span
                                        class="px-3 py-1 text-xs font-semibold rounded-full bg-orange-900 text-orange-300">{{ $ticket->priority_label }}</span>
                                @else
                                    <span
                                        class="px-3 py-1 text-xs font-semibold rounded-full bg-red-900 text-red-300">{{ $ticket->priority_label }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($ticket->status === 'open')
                                    <span
                                        class="px-3 py-1 text-xs font-semibold rounded-full bg-cyan-900 text-cyan-300">{{ $ticket->status_label }}</span>
                                @elseif($ticket->status === 'in_progress')
                                    <span
                                        class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-900 text-yellow-300">{{ $ticket->status_label }}</span>
                                @elseif($ticket->status === 'resolved')
                                    <span
                                        class="px-3 py-1 text-xs font-semibold rounded-full bg-green-900 text-green-300">{{ $ticket->status_label }}</span>
                                @else
                                    <span
                                        class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-700 text-gray-300">{{ $ticket->status_label }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-300">{{ $ticket->assignedTo->name ?? 'Belum ditugaskan' }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('tickets.show', $ticket) }}"
                                    class="inline-flex items-center px-3 py-1.5 bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white text-xs font-medium rounded-lg transition-all">
                                    Lihat
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-gray-400">
                                <svg class="mx-auto h-12 w-12 text-gray-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                                    </path>
                                </svg>
                                <p class="mt-2">Belum ada tiket</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Tickets by Category -->
    <div class="bg-gray-800 rounded-xl border border-gray-700 p-6">
        <h2 class="text-xl font-bold text-white mb-6">Tiket per Kategori</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($ticketsByCategory as $category)
                <div class="bg-gray-700 rounded-lg p-4 hover:bg-gray-600 transition-colors">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <span class="text-2xl">{{ $category->icon }}</span>
                            <span class="text-sm font-medium text-white">{{ $category->name }}</span>
                        </div>
                        <span class="text-lg font-bold text-purple-400">{{ $category->tickets_count }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection