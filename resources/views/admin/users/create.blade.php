@extends('layouts.app')

@section('title', 'Tambah Pengguna')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.users') }}"
                class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-gray-800 hover:bg-gray-700 text-gray-400 hover:text-gray-100 transition-colors border border-gray-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-100">Tambah Pengguna</h1>
                <p class="mt-1 text-sm text-gray-400">Buat akun pengguna baru</p>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-gray-800 rounded-lg shadow-xl border border-gray-700">
            <div class="p-6 border-b border-gray-700 bg-gradient-to-r from-purple-500 to-pink-500">
                <h2 class="text-lg font-semibold text-white">Informasi Pengguna</h2>
            </div>

            <form action="{{ route('admin.users.store') }}" method="POST" class="p-6 space-y-6">
                @csrf

                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-300 mb-2">
                        Nama Lengkap <span class="text-red-400">*</span>
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                        class="w-full px-4 py-2.5 bg-gray-900 border border-gray-700 rounded-lg text-gray-100 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                        placeholder="Masukkan nama lengkap">
                    @error('name')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-300 mb-2">
                        Email <span class="text-red-400">*</span>
                    </label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
                        class="w-full px-4 py-2.5 bg-gray-900 border border-gray-700 rounded-lg text-gray-100 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                        placeholder="contoh@email.com">
                    @error('email')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-300 mb-2">
                        Password <span class="text-red-400">*</span>
                    </label>
                    <input type="password" name="password" id="password" required
                        class="w-full px-4 py-2.5 bg-gray-900 border border-gray-700 rounded-lg text-gray-100 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                        placeholder="Minimal 8 karakter">
                    @error('password')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Confirmation -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-300 mb-2">
                        Konfirmasi Password <span class="text-red-400">*</span>
                    </label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                        class="w-full px-4 py-2.5 bg-gray-900 border border-gray-700 rounded-lg text-gray-100 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                        placeholder="Ketik ulang password">
                </div>

                <!-- Role -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-3">
                        Role <span class="text-red-400">*</span>
                    </label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Admin Role -->
                        <label
                            class="relative flex items-start p-4 bg-gray-900 border-2 border-gray-700 rounded-lg cursor-pointer hover:border-red-500 transition-all group">
                            <input type="radio" name="role" value="admin" {{ old('role') == 'admin' ? 'checked' : '' }}
                                class="mt-0.5 h-4 w-4 text-red-500 border-gray-600 focus:ring-red-500 focus:ring-offset-0 focus:ring-offset-gray-900">
                            <div class="ml-3">
                                <div class="flex items-center">
                                    <span class="text-sm font-medium text-gray-100">Admin</span>
                                    <span
                                        class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-900 text-red-300 border border-red-700">
                                        Full Access
                                    </span>
                                </div>
                                <p class="mt-1 text-xs text-gray-400">Akses penuh ke sistem</p>
                            </div>
                        </label>

                        <!-- General Manager Role -->
                        <label
                            class="relative flex items-start p-4 bg-gray-900 border-2 border-gray-700 rounded-lg cursor-pointer hover:border-purple-500 transition-all group">
                            <input type="radio" name="role" value="general_manager" {{ old('role') == 'general_manager' ? 'checked' : '' }}
                                class="mt-0.5 h-4 w-4 text-purple-500 border-gray-600 focus:ring-purple-500 focus:ring-offset-0 focus:ring-offset-gray-900">
                            <div class="ml-3">
                                <div class="flex items-center">
                                    <span class="text-sm font-medium text-gray-100">General Manager</span>
                                    <span
                                        class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-900 text-purple-300 border border-purple-700">
                                        Manager
                                    </span>
                                </div>
                                <p class="mt-1 text-xs text-gray-400">View reports & analytics</p>
                            </div>
                        </label>

                        <!-- Technician Role -->
                        <label
                            class="relative flex items-start p-4 bg-gray-900 border-2 border-gray-700 rounded-lg cursor-pointer hover:border-blue-500 transition-all group">
                            <input type="radio" name="role" value="technician" {{ old('role') == 'technician' ? 'checked' : '' }}
                                class="mt-0.5 h-4 w-4 text-blue-500 border-gray-600 focus:ring-blue-500 focus:ring-offset-0 focus:ring-offset-gray-900">
                            <div class="ml-3">
                                <div class="flex items-center">
                                    <span class="text-sm font-medium text-gray-100">Teknisi</span>
                                    <span
                                        class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-900 text-blue-300 border border-blue-700">
                                        Support
                                    </span>
                                </div>
                                <p class="mt-1 text-xs text-gray-400">Handle & resolve tickets</p>
                            </div>
                        </label>

                        <!-- User Role -->
                        <label
                            class="relative flex items-start p-4 bg-gray-900 border-2 border-gray-700 rounded-lg cursor-pointer hover:border-green-500 transition-all group">
                            <input type="radio" name="role" value="user" {{ old('role', 'user') == 'user' ? 'checked' : '' }}
                                class="mt-0.5 h-4 w-4 text-green-500 border-gray-600 focus:ring-green-500 focus:ring-offset-0 focus:ring-offset-gray-900">
                            <div class="ml-3">
                                <div class="flex items-center">
                                    <span class="text-sm font-medium text-gray-100">User</span>
                                    <span
                                        class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-900 text-green-300 border border-green-700">
                                        Standard
                                    </span>
                                </div>
                                <p class="mt-1 text-xs text-gray-400">Create & view own tickets</p>
                            </div>
                        </label>
                    </div>
                    @error('role')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-700">
                    <a href="{{ route('admin.users') }}"
                        class="px-6 py-2.5 bg-gray-700 hover:bg-gray-600 text-gray-100 font-medium rounded-lg transition-colors">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-6 py-2.5 bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white font-medium rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl">
                        Simpan Pengguna
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection