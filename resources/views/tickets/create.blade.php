@extends('layouts.app')

@section('title', 'Buat Tiket Baru')

@section('content')
<!-- Header -->
<div class="mb-8">
    <h1 class="text-2xl font-bold text-white mb-2">Buat Tiket Baru</h1>
    <p class="text-gray-400">Laporkan masalah IT Anda dan dapatkan bantuan dari tim support</p>
</div>

<!-- Form Card -->
<div class="max-w-3xl">
    <div class="bg-gray-800 rounded-xl border border-gray-700 p-8">
        <form method="POST" action="{{ route('tickets.store') }}" enctype="multipart/form-data">
            @csrf
            
            <!-- Title -->
            <div class="mb-6">
                <label for="title" class="block text-sm font-medium text-gray-300 mb-2">
                    Judul Tiket <span class="text-red-400">*</span>
                </label>
                <input 
                    type="text" 
                    id="title" 
                    name="title" 
                    value="{{ old('title') }}"
                    class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                    placeholder="Contoh: Laptop tidak bisa menyala"
                    required
                >
                @error('title')
                    <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Category -->
            <div class="mb-6">
                <label for="category_id" class="block text-sm font-medium text-gray-300 mb-2">
                    Kategori <span class="text-red-400">*</span>
                </label>
                <select 
                    id="category_id" 
                    name="category_id"
                    class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                    required
                >
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->icon }} {{ $category->name }}
                    </option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Priority -->
            <div class="mb-6">
                <label for="priority" class="block text-sm font-medium text-gray-300 mb-2">
                    Prioritas <span class="text-red-400">*</span>
                </label>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <label class="relative cursor-pointer">
                        <input 
                            type="radio" 
                            name="priority" 
                            value="low" 
                            {{ old('priority') == 'low' ? 'checked' : '' }}
                            class="peer sr-only"
                        >
                        <div class="p-4 bg-gray-700 border-2 border-gray-600 rounded-lg text-center peer-checked:border-green-500 peer-checked:bg-green-900 transition">
                            <div class="text-2xl mb-1">🟢</div>
                            <div class="text-sm font-medium text-white">Rendah</div>
                        </div>
                    </label>
                    <label class="relative cursor-pointer">
                        <input 
                            type="radio" 
                            name="priority" 
                            value="medium" 
                            {{ old('priority', 'medium') == 'medium' ? 'checked' : '' }}
                            class="peer sr-only"
                        >
                        <div class="p-4 bg-gray-700 border-2 border-gray-600 rounded-lg text-center peer-checked:border-yellow-500 peer-checked:bg-yellow-900 transition">
                            <div class="text-2xl mb-1">🟡</div>
                            <div class="text-sm font-medium text-white">Sedang</div>
                        </div>
                    </label>
                    <label class="relative cursor-pointer">
                        <input 
                            type="radio" 
                            name="priority" 
                            value="high" 
                            {{ old('priority') == 'high' ? 'checked' : '' }}
                            class="peer sr-only"
                        >
                        <div class="p-4 bg-gray-700 border-2 border-gray-600 rounded-lg text-center peer-checked:border-orange-500 peer-checked:bg-orange-900 transition">
                            <div class="text-2xl mb-1">🟠</div>
                            <div class="text-sm font-medium text-white">Tinggi</div>
                        </div>
                    </label>
                    <label class="relative cursor-pointer">
                        <input 
                            type="radio" 
                            name="priority" 
                            value="critical" 
                            {{ old('priority') == 'critical' ? 'checked' : '' }}
                            class="peer sr-only"
                        >
                        <div class="p-4 bg-gray-700 border-2 border-gray-600 rounded-lg text-center peer-checked:border-red-500 peer-checked:bg-red-900 transition">
                            <div class="text-2xl mb-1">🔴</div>
                            <div class="text-sm font-medium text-white">Kritis</div>
                        </div>
                    </label>
                </div>
                @error('priority')
                    <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div class="mb-8">
                <label for="description" class="block text-sm font-medium text-gray-300 mb-2">
                    Deskripsi <span class="text-red-400">*</span>
                </label>
                <textarea 
                    id="description" 
                    name="description" 
                    rows="6"
                    class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition resize-none"
                    placeholder="Jelaskan masalah Anda secara detail..."
                    required
                >{{ old('description') }}</textarea>
                <p class="mt-2 text-sm text-gray-400">Berikan informasi sebanyak mungkin agar kami bisa membantu dengan lebih cepat</p>
                @error('description')
                    <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Attachments -->
            <div class="mb-8">
                <label for="attachments" class="block text-sm font-medium text-gray-300 mb-2">
                    Lampiran (Foto/Dokumen) <span class="text-red-400">*</span>
                </label>
                <input
                    type="file"
                    id="attachments"
                    name="attachments[]"
                    multiple
                    class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                    required
                >
                <p class="mt-2 text-sm text-gray-400">Unggah minimal 1 file (contoh: foto, PDF, DOC/DOCX, XLS/XLSX).</p>
                @error('attachments')
                    <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                @enderror
                @error('attachments.*')
                    <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4">
                <button 
                    type="submit"
                    class="flex-1 sm:flex-none px-6 py-3 bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white font-semibold rounded-lg transition-all transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 focus:ring-offset-gray-800"
                >
                    <span class="flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Buat Tiket
                    </span>
                </button>
                <a 
                    href="{{ route('tickets.index') }}"
                    class="flex-1 sm:flex-none px-6 py-3 bg-gray-700 hover:bg-gray-600 text-white font-semibold rounded-lg transition-all text-center"
                >
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
