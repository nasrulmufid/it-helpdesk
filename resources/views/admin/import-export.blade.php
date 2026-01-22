@extends('layouts.app')

@section('title', 'Migrasi Data')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div>
        <h1 class="text-2xl font-bold text-white mb-2">Migrasi Data</h1>
        <p class="text-gray-400">Import dan Export data sistem dalam format CSV</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Users Card -->
        <div class="bg-gray-800 rounded-xl border border-gray-700 overflow-hidden shadow-lg">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-purple-900/30 rounded-lg text-purple-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Master Data</span>
                </div>
                <h3 class="text-lg font-bold text-white mb-2">Pengguna (Users)</h3>
                <p class="text-sm text-gray-400 mb-6">Kelola data user, teknisi, dan admin melalui file CSV.</p>
                
                <div class="space-y-4">
                    <a href="{{ route('admin.migration.template', 'users') }}" class="block w-full text-center px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white text-sm font-medium rounded-lg transition">
                        Unduh Template
                    </a>
                    <a href="{{ route('admin.migration.export', 'users') }}" class="block w-full text-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition">
                        Export ke CSV
                    </a>
                    
                    <div class="pt-4 border-t border-gray-700">
                        <form action="{{ route('admin.migration.import', 'users') }}" method="POST" enctype="multipart/form-data" class="space-y-2">
                            @csrf
                            <label class="block text-xs font-medium text-gray-400 uppercase mb-1">Import CSV</label>
                            <input type="file" name="file" accept=".csv" required class="block w-full text-xs text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-gray-700 file:text-gray-300 hover:file:bg-gray-600 cursor-pointer" />
                            <button type="submit" class="w-full px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition">
                                Jalankan Import
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Categories Card -->
        <div class="bg-gray-800 rounded-xl border border-gray-700 overflow-hidden shadow-lg">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-blue-900/30 rounded-lg text-blue-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                    </div>
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Master Data</span>
                </div>
                <h3 class="text-lg font-bold text-white mb-2">Kategori (Categories)</h3>
                <p class="text-sm text-gray-400 mb-6">Kelola kategori tiket dan klasifikasi masalah.</p>
                
                <div class="space-y-4">
                    <a href="{{ route('admin.migration.template', 'categories') }}" class="block w-full text-center px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white text-sm font-medium rounded-lg transition">
                        Unduh Template
                    </a>
                    <a href="{{ route('admin.migration.export', 'categories') }}" class="block w-full text-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition">
                        Export ke CSV
                    </a>
                    
                    <div class="pt-4 border-t border-gray-700">
                        <form action="{{ route('admin.migration.import', 'categories') }}" method="POST" enctype="multipart/form-data" class="space-y-2">
                            @csrf
                            <label class="block text-xs font-medium text-gray-400 uppercase mb-1">Import CSV</label>
                            <input type="file" name="file" accept=".csv" required class="block w-full text-xs text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-gray-700 file:text-gray-300 hover:file:bg-gray-600 cursor-pointer" />
                            <button type="submit" class="w-full px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition">
                                Jalankan Import
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tickets Card -->
        <div class="bg-gray-800 rounded-xl border border-gray-700 overflow-hidden shadow-lg">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-pink-900/30 rounded-lg text-pink-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                        </svg>
                    </div>
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Transactional Data</span>
                </div>
                <h3 class="text-lg font-bold text-white mb-2">Tiket (Tickets)</h3>
                <p class="text-sm text-gray-400 mb-6">Migrasi data tiket historis atau bulk creation tiket.</p>
                
                <div class="space-y-4">
                    <a href="{{ route('admin.migration.template', 'tickets') }}" class="block w-full text-center px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white text-sm font-medium rounded-lg transition">
                        Unduh Template
                    </a>
                    <a href="{{ route('admin.migration.export', 'tickets') }}" class="block w-full text-center px-4 py-2 bg-pink-600 hover:bg-pink-700 text-white text-sm font-medium rounded-lg transition">
                        Export ke CSV
                    </a>
                    
                    <div class="pt-4 border-t border-gray-700">
                        <form action="{{ route('admin.migration.import', 'tickets') }}" method="POST" enctype="multipart/form-data" class="space-y-2">
                            @csrf
                            <label class="block text-xs font-medium text-gray-400 uppercase mb-1">Import CSV</label>
                            <input type="file" name="file" accept=".csv" required class="block w-full text-xs text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-gray-700 file:text-gray-300 hover:file:bg-gray-600 cursor-pointer" />
                            <button type="submit" class="w-full px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition">
                                Jalankan Import
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Instructions -->
    <div class="bg-gray-800 rounded-xl border border-gray-700 p-6">
        <h3 class="text-lg font-bold text-white mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Petunjuk Penting
        </h3>
        <ul class="space-y-2 text-sm text-gray-400">
            <li class="flex items-start">
                <span class="mr-2">•</span>
                <span>Gunakan file template CSV yang disediakan untuk memastikan format kolom sesuai.</span>
            </li>
            <li class="flex items-start">
                <span class="mr-2">•</span>
                <span>Untuk data <strong>Users</strong>, password akan di-hash secara otomatis saat diimport.</span>
            </li>
            <li class="flex items-start">
                <span class="mr-2">•</span>
                <span>Untuk data <strong>Tickets</strong>, pastikan email user dan nama kategori sudah terdaftar di sistem.</span>
            </li>
            <li class="flex items-start">
                <span class="mr-2">•</span>
                <span>Sistem akan melewati baris jika email user sudah terdaftar (untuk import Users).</span>
            </li>
        </ul>
    </div>
</div>
@endsection
