@extends('layouts.app')

@section('title', 'Laporan Performa Teknisi')

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
                    <h2 class="text-2xl font-bold text-white">Laporan Performa Teknisi</h2>
                </div>
                <p class="text-gray-400 mt-1 ml-9">Evaluasi kinerja setiap teknisi</p>
            </div>
            <a href="{{ route('reports.export-pdf', ['type' => 'technician-performance', 'start_date' => $startDate, 'end_date' => $endDate]) }}"
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
            <form action="{{ route('reports.technician-performance') }}" method="GET"
                class="flex flex-col md:flex-row gap-4 items-end">
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
                <button type="submit"
                    class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                    Filter
                </button>
            </form>
        </div>

        <!-- Technician Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($technicianStats as $stat)
                <div class="bg-gray-800 rounded-xl p-6 border border-gray-700">
                    <div class="flex items-center mb-4">
                        <div
                            class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-500 rounded-full flex items-center justify-center">
                            <span
                                class="text-white font-semibold text-lg">{{ strtoupper(substr($stat['technician']->name, 0, 2)) }}</span>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-lg font-semibold text-white">{{ $stat['technician']->name }}</h4>
                            <p class="text-sm text-gray-400">{{ $stat['technician']->email }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div class="bg-gray-700/50 rounded-lg p-3 text-center">
                            <p class="text-2xl font-bold text-blue-400">{{ $stat['total_assigned'] }}</p>
                            <p class="text-xs text-gray-400">Total Ditugaskan</p>
                        </div>
                        <div class="bg-gray-700/50 rounded-lg p-3 text-center">
                            <p class="text-2xl font-bold text-green-400">{{ $stat['resolved'] }}</p>
                            <p class="text-xs text-gray-400">Terselesaikan</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-gray-700/50 rounded-lg p-3 text-center">
                            <p class="text-2xl font-bold text-yellow-400">{{ $stat['in_progress'] }}</p>
                            <p class="text-xs text-gray-400">Dalam Proses</p>
                        </div>
                        <div class="bg-gray-700/50 rounded-lg p-3 text-center">
                            <p class="text-2xl font-bold text-purple-400">{{ $stat['response_count'] }}</p>
                            <p class="text-xs text-gray-400">Total Respon</p>
                        </div>
                    </div>

                    <div class="mt-4 pt-4 border-t border-gray-700">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-400">Rata-rata Resolusi</span>
                            <span class="text-sm font-medium text-white">{{ $stat['avg_resolution_time'] }} jam</span>
                        </div>
                        @if($stat['total_assigned'] > 0)
                            <div class="mt-2">
                                <div class="flex justify-between text-xs text-gray-400 mb-1">
                                    <span>Tingkat Penyelesaian</span>
                                    <span>{{ $stat['total_assigned'] > 0 ? round(($stat['resolved'] / $stat['total_assigned']) * 100) : 0 }}%</span>
                                </div>
                                <div class="w-full bg-gray-700 rounded-full h-2">
                                    <div class="bg-green-500 h-2 rounded-full"
                                        style="width: {{ $stat['total_assigned'] > 0 ? ($stat['resolved'] / $stat['total_assigned']) * 100 : 0 }}%">
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="bg-gray-800 rounded-xl p-8 text-center border border-gray-700">
                        <svg class="w-16 h-16 text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <p class="text-gray-400">Tidak ada data teknisi</p>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Summary Table -->
        <div class="bg-gray-800 rounded-xl border border-gray-700">
            <div class="p-6 border-b border-gray-700">
                <h3 class="text-lg font-semibold text-white">Ringkasan Performa Teknisi</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                Teknisi</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-300 uppercase tracking-wider">
                                Total Tiket</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-300 uppercase tracking-wider">
                                Terselesaikan</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-300 uppercase tracking-wider">
                                Dalam Proses</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-300 uppercase tracking-wider">
                                Total Respon</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-300 uppercase tracking-wider">
                                Rata-rata Resolusi</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-300 uppercase tracking-wider">
                                Tingkat Penyelesaian</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        @forelse($technicianStats as $stat)
                            <tr class="hover:bg-gray-700/50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div
                                            class="w-8 h-8 bg-gradient-to-br from-purple-500 to-pink-500 rounded-full flex items-center justify-center">
                                            <span
                                                class="text-white font-semibold text-xs">{{ strtoupper(substr($stat['technician']->name, 0, 2)) }}</span>
                                        </div>
                                        <span class="ml-3 text-sm font-medium text-white">{{ $stat['technician']->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-300">
                                    {{ $stat['total_assigned'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span
                                        class="px-2 py-1 text-xs font-medium rounded-full bg-green-500/20 text-green-400">{{ $stat['resolved'] }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span
                                        class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-500/20 text-yellow-400">{{ $stat['in_progress'] }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-300">
                                    {{ $stat['response_count'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-300">
                                    {{ $stat['avg_resolution_time'] }} jam</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @php
                                        $rate = $stat['total_assigned'] > 0 ? round(($stat['resolved'] / $stat['total_assigned']) * 100) : 0;
                                        $rateColor = $rate >= 80 ? 'text-green-400' : ($rate >= 50 ? 'text-yellow-400' : 'text-red-400');
                                    @endphp
                                    <span class="text-sm font-medium {{ $rateColor }}">{{ $rate }}%</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-gray-400">
                                    Tidak ada data teknisi
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection