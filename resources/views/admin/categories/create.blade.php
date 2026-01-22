@extends('layouts.app')

@section('title', 'Tambah Kategori')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.categories') }}"
                class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-gray-800 hover:bg-gray-700 text-gray-400 hover:text-gray-100 transition-colors border border-gray-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-100">Tambah Kategori</h1>
                <p class="mt-1 text-sm text-gray-400">Buat kategori tiket baru</p>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-gray-800 rounded-lg shadow-xl border border-gray-700">
            <div class="p-6 border-b border-gray-700 bg-gradient-to-r from-purple-500 to-pink-500">
                <h2 class="text-lg font-semibold text-white">Informasi Kategori</h2>
            </div>

            <form action="{{ route('admin.categories.store') }}" method="POST" class="p-6 space-y-6">
                @csrf

                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-300 mb-2">
                        Nama Kategori <span class="text-red-400">*</span>
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                        class="w-full px-4 py-2.5 bg-gray-900 border border-gray-700 rounded-lg text-gray-100 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                        placeholder="Contoh: Hardware, Software, Network">
                    @error('name')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Slug -->
                <div>
                    <label for="slug" class="block text-sm font-medium text-gray-300 mb-2">
                        Slug <span class="text-gray-500 text-xs">(opsional, akan dibuat otomatis)</span>
                    </label>
                    <input type="text" name="slug" id="slug" value="{{ old('slug') }}"
                        class="w-full px-4 py-2.5 bg-gray-900 border border-gray-700 rounded-lg text-gray-100 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all font-mono text-sm"
                        placeholder="hardware-issues">
                    <p class="mt-1 text-xs text-gray-400">URL-friendly version dari nama kategori</p>
                    @error('slug')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Icon -->
                <div>
                    <label for="icon" class="block text-sm font-medium text-gray-300 mb-2">
                        Icon/Emoji <span class="text-red-400">*</span>
                    </label>
                    <div class="flex items-center space-x-3">
                        <input type="text" name="icon" id="icon" value="{{ old('icon', '📁') }}" required maxlength="2"
                            class="w-20 px-4 py-2.5 bg-gray-900 border border-gray-700 rounded-lg text-gray-100 text-center text-2xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                        <div class="flex-1">
                            <p class="text-xs text-gray-400">Pilih emoji atau icon untuk kategori</p>
                            <div class="mt-2 flex flex-wrap gap-2">
                                <button type="button" onclick="document.getElementById('icon').value='💻'"
                                    class="px-3 py-1.5 bg-gray-900 hover:bg-gray-700 border border-gray-700 rounded text-xl transition-colors">💻</button>
                                <button type="button" onclick="document.getElementById('icon').value='🖥️'"
                                    class="px-3 py-1.5 bg-gray-900 hover:bg-gray-700 border border-gray-700 rounded text-xl transition-colors">🖥️</button>
                                <button type="button" onclick="document.getElementById('icon').value='🔧'"
                                    class="px-3 py-1.5 bg-gray-900 hover:bg-gray-700 border border-gray-700 rounded text-xl transition-colors">🔧</button>
                                <button type="button" onclick="document.getElementById('icon').value='🌐'"
                                    class="px-3 py-1.5 bg-gray-900 hover:bg-gray-700 border border-gray-700 rounded text-xl transition-colors">🌐</button>
                                <button type="button" onclick="document.getElementById('icon').value='🔐'"
                                    class="px-3 py-1.5 bg-gray-900 hover:bg-gray-700 border border-gray-700 rounded text-xl transition-colors">🔐</button>
                                <button type="button" onclick="document.getElementById('icon').value='📱'"
                                    class="px-3 py-1.5 bg-gray-900 hover:bg-gray-700 border border-gray-700 rounded text-xl transition-colors">📱</button>
                                <button type="button" onclick="document.getElementById('icon').value='🖨️'"
                                    class="px-3 py-1.5 bg-gray-900 hover:bg-gray-700 border border-gray-700 rounded text-xl transition-colors">🖨️</button>
                                <button type="button" onclick="document.getElementById('icon').value='📁'"
                                    class="px-3 py-1.5 bg-gray-900 hover:bg-gray-700 border border-gray-700 rounded text-xl transition-colors">📁</button>
                            </div>
                        </div>
                    </div>
                    @error('icon')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-300 mb-2">
                        Deskripsi
                    </label>
                    <textarea name="description" id="description" rows="4"
                        class="w-full px-4 py-2.5 bg-gray-900 border border-gray-700 rounded-lg text-gray-100 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all resize-none"
                        placeholder="Deskripsi singkat tentang kategori ini...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                            class="h-5 w-5 text-purple-500 border-gray-600 rounded focus:ring-purple-500 focus:ring-offset-0 focus:ring-offset-gray-900">
                        <span class="ml-3">
                            <span class="text-sm font-medium text-gray-300">Kategori Aktif</span>
                            <span class="block text-xs text-gray-400">Pengguna dapat memilih kategori ini saat membuat
                                tiket</span>
                        </span>
                    </label>
                    @error('is_active')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-700">
                    <a href="{{ route('admin.categories') }}"
                        class="px-6 py-2.5 bg-gray-700 hover:bg-gray-600 text-gray-100 font-medium rounded-lg transition-colors">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-6 py-2.5 bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white font-medium rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl">
                        Simpan Kategori
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection