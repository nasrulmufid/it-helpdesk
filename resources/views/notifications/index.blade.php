@extends('layouts.app')

@section('title', 'Notifikasi')

@section('content')
    <div class="max-w-5xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-400">Pantau aktivitas tiket dan tugas Anda</p>
                <p class="text-[10px] text-gray-500 mt-1 flex items-center gap-1">
                    <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Notifikasi otomatis dihapus setelah 30 hari</span>
                </p>
            </div>
            <form action="{{ route('notifications.readAll') }}" method="POST">
                @csrf
                <button type="submit"
                    class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg text-sm font-semibold">
                    Tandai semua dibaca
                </button>
            </form>
        </div>

        <div class="bg-gray-800 border border-gray-700 rounded-xl shadow-lg overflow-hidden">
            <div class="divide-y divide-gray-700">
                @forelse($notifications as $notification)
                    @php
                        $data = $notification->data;
                    @endphp
                    <div class="flex items-start gap-4 px-6 py-4 {{ $notification->read_at ? 'bg-gray-800' : 'bg-gray-700' }}">
                        <div class="pt-1">
                            <span
                                class="inline-flex items-center justify-center w-10 h-10 rounded-full {{ $notification->read_at ? 'bg-gray-700' : 'bg-purple-600' }} text-white font-semibold">
                                {{ strtoupper(substr($data['type'] ?? 'N', 0, 1)) }}
                            </span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-semibold text-white">{{ $data['title'] ?? 'Tiket' }}</p>
                                <span class="text-xs text-gray-400">{{ $notification->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="mt-1 text-sm text-gray-300">{{ $data['message'] ?? '' }}</p>
                            <div class="mt-2 flex items-center gap-3 text-xs text-gray-400">
                                @if(!empty($data['ticket_number']))
                                    <span
                                        class="px-2 py-1 rounded-full bg-gray-700 text-purple-200">{{ $data['ticket_number'] }}</span>
                                @endif
                                @if(!empty($data['by_user_name']))
                                    <span>Oleh {{ $data['by_user_name'] }}</span>
                                @endif
                            </div>
                            @if(!empty($data['url']))
                                <a href="{{ $data['url'] }}"
                                    class="inline-flex items-center mt-3 text-sm text-purple-300 hover:text-purple-200 font-semibold">
                                    Lihat detail
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            @endif
                        </div>
                        @if(!$notification->read_at)
                            <form action="{{ route('notifications.read', $notification) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="text-xs px-3 py-1 rounded-lg bg-purple-600 hover:bg-purple-500 text-white font-semibold">Tandai
                                    dibaca</button>
                            </form>
                        @endif
                    </div>
                @empty
                    <div class="px-6 py-8 text-center text-gray-400">
                        Belum ada notifikasi.
                    </div>
                @endforelse
            </div>
        </div>

        <div>
            {{ $notifications->links() }}
        </div>
    </div>
@endsection