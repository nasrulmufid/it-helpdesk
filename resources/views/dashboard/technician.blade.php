@extends('layouts.app')

@section('title', 'Dashboard Teknisi')

@section('content')
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Tiket Saya -->
        <a href="{{ route('tickets.index', ['assigned_to' => auth()->id()]) }}"
            class="bg-gray-800 rounded-xl p-6 border border-gray-700 hover:border-purple-500 transition-all duration-300 transform hover:scale-105 cursor-pointer group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm font-medium group-hover:text-purple-400 transition-colors">Tiket Saya
                    </p>
                    <h3 class="text-3xl font-bold text-white mt-2">{{ $stats['assigned_tickets'] }}</h3>
                </div>
                <div
                    class="bg-gradient-to-br from-purple-500 to-purple-600 p-3 rounded-lg group-hover:shadow-lg group-hover:shadow-purple-500/50 transition-all">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                        </path>
                    </svg>
                </div>
            </div>
        </a>

        <!-- Terbuka -->
        <a href="{{ route('tickets.index', ['status' => 'open']) }}"
            class="bg-gray-800 rounded-xl p-6 border border-gray-700 hover:border-cyan-500 transition-all duration-300 transform hover:scale-105 cursor-pointer group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm font-medium group-hover:text-cyan-400 transition-colors">Terbuka</p>
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
    </div>

    <!-- My Assigned Tickets -->
    <div class="bg-gray-800 rounded-xl border border-gray-700 overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-700 flex items-center justify-between">
            <h2 class="text-xl font-bold text-white">Tiket yang Ditugaskan kepada Saya</h2>
            <a href="{{ route('tickets.index') }}" class="text-sm text-purple-400 hover:text-purple-300 transition">
                Lihat Semua →
            </a>
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    @forelse($assignedTickets as $ticket)
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
                                <a href="{{ route('tickets.show', $ticket) }}"
                                    class="inline-flex items-center px-3 py-1.5 bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white text-xs font-medium rounded-lg transition-all">
                                    Lihat
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                                <svg class="mx-auto h-12 w-12 text-gray-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                                    </path>
                                </svg>
                                <p class="mt-2">Belum ada tiket yang ditugaskan</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Unassigned Tickets -->
    <div class="bg-gray-800 rounded-xl border border-gray-700 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-700">
            <h2 class="text-xl font-bold text-white">Tiket Belum Ditugaskan</h2>
            <p class="text-sm text-gray-400 mt-1">Tiket yang menunggu untuk diambil</p>
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    @forelse($unassignedTickets as $ticket)
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
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('tickets.show', $ticket) }}"
                                        class="inline-flex items-center px-3 py-1.5 bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white text-xs font-medium rounded-lg transition-all">
                                        Lihat
                                    </a>
                                    <form action="{{ route('tickets.assignToSelf', $ticket) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="inline-flex items-center px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-medium rounded-lg transition-all">
                                            Ambil
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                <svg class="mx-auto h-12 w-12 text-gray-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="mt-2">Semua tiket sudah ditugaskan</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection