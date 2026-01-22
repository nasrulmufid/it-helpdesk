@extends('layouts.app')

@section('title', 'Daftar Tiket')

@section('content')
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-white mb-2">Daftar Tiket</h1>
            <p class="text-gray-400">Kelola dan pantau semua tiket support</p>
        </div>
        @if(auth()->user()->isUser())
            <a href="{{ route('tickets.create') }}"
                class="mt-4 md:mt-0 inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white font-semibold rounded-lg transition-all transform hover:scale-105">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Buat Tiket Baru
            </a>
        @endif
    </div>

    <!-- Tickets Table -->
    <div class="bg-gray-800 rounded-xl border border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">No. Tiket
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Judul
                        </th>
                        @if(!auth()->user()->isUser())
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Pengguna
                            </th>
                        @endif
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Kategori
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Prioritas
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Status
                        </th>
                        @if(!auth()->user()->isUser())
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                Ditugaskan</th>
                        @endif
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Dibuat
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    @forelse($tickets as $ticket)
                        <tr class="hover:bg-gray-700 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-medium text-purple-400">{{ $ticket->ticket_number }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-white font-medium">{{ Str::limit($ticket->title, 50) }}</p>
                            </td>
                            @if(!auth()->user()->isUser())
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div
                                            class="w-8 h-8 bg-gradient-to-br from-purple-500 to-pink-500 rounded-full flex items-center justify-center mr-2">
                                            <span
                                                class="text-white text-xs font-semibold">{{ strtoupper(substr($ticket->user->name, 0, 2)) }}</span>
                                        </div>
                                        <span class="text-sm text-gray-300">{{ $ticket->user->name }}</span>
                                    </div>
                                </td>
                            @endif
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
                            @if(!auth()->user()->isUser())
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($ticket->assignedTo)
                                        <div class="flex items-center">
                                            <div
                                                class="w-6 h-6 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center mr-2">
                                                <span
                                                    class="text-white text-xs font-semibold">{{ strtoupper(substr($ticket->assignedTo->name, 0, 1)) }}</span>
                                            </div>
                                            <span class="text-sm text-gray-300">{{ $ticket->assignedTo->name }}</span>
                                        </div>
                                    @else
                                        <span class="text-sm text-gray-500 italic">Belum ditugaskan</span>
                                    @endif
                                </td>
                            @endif
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-400">{{ $ticket->created_at->format('d/m/Y H:i') }}</span>
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
                            <td colspan="9" class="px-6 py-12 text-center text-gray-400">
                                <svg class="mx-auto h-12 w-12 text-gray-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                                    </path>
                                </svg>
                                <p class="mt-2">Belum ada tiket</p>
                                @if(auth()->user()->isUser())
                                    <a href="{{ route('tickets.create') }}"
                                        class="mt-4 inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white text-sm font-medium rounded-lg transition-all">
                                        Buat Tiket Pertama
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($tickets->hasPages())
        <div class="mt-6 flex justify-center">
            {{ $tickets->links() }}
        </div>
    @endif
@endsection