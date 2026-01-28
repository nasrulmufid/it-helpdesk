<!DOCTYPE html>
<html lang="id" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'IT Help Desk')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-900 text-gray-100">
    @if(auth()->check())
        <div class="flex h-screen overflow-hidden">
            <!-- Sidebar -->
            <aside id="sidebar"
                class="fixed inset-y-0 left-0 z-50 w-64 bg-gray-800 border-r border-gray-700 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out custom-scrollbar overflow-y-auto">
                <div class="flex flex-col h-full">
                    <!-- Logo -->
                    <div class="flex items-center justify-between p-6 border-b border-gray-700">
                        <a href="{{ route('dashboard') }}" class="flex items-center space-x-3">
                            <div
                                class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-500 rounded-lg flex items-center justify-center">
                                <span class="text-white font-bold text-xl">HD</span>
                            </div>
                            <span class="text-xl font-bold text-white">Help Desk</span>
                        </a>
                        <button id="closeSidebar" class="lg:hidden text-gray-400 hover:text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Navigation -->
                    <nav class="flex-1 px-4 py-6 space-y-2">
                        <a href="{{ route('dashboard') }}"
                            class="flex items-center px-4 py-3 text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white transition-colors {{ request()->routeIs('dashboard') ? 'bg-gray-700 text-white' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                                </path>
                            </svg>
                            Dashboard
                        </a>

                        <a href="{{ route('tickets.index') }}"
                            class="flex items-center px-4 py-3 text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white transition-colors {{ request()->routeIs('tickets.*') ? 'bg-gray-700 text-white' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z">
                                </path>
                            </svg>
                            Tiket
                        </a>

                        @if(auth()->user()->isAdmin() || auth()->user()->isGeneralManager())
                            <a href="{{ route('reports.index') }}"
                                class="flex items-center px-4 py-3 text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white transition-colors {{ request()->routeIs('reports.*') ? 'bg-gray-700 text-white' : '' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                Laporan
                            </a>
                        @endif

                        @if(auth()->user()->isAdmin())
                            <div class="pt-4 mt-4 border-t border-gray-700">
                                <p class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Admin</p>
                                <a href="{{ route('admin.users') }}"
                                    class="flex items-center px-4 py-3 mt-2 text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white transition-colors {{ request()->routeIs('admin.users') ? 'bg-gray-700 text-white' : '' }}">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                        </path>
                                    </svg>
                                    Kelola Pengguna
                                </a>
                                <a href="{{ route('admin.categories') }}"
                                    class="flex items-center px-4 py-3 text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white transition-colors {{ request()->routeIs('admin.categories') ? 'bg-gray-700 text-white' : '' }}">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                                        </path>
                                    </svg>
                                    Kelola Kategori
                                </a>
                                <a href="{{ route('admin.migration.index') }}"
                                    class="flex items-center px-4 py-3 text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white transition-colors {{ request()->routeIs('admin.migration.*') ? 'bg-gray-700 text-white' : '' }}">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                    </svg>
                                    Migrasi Data
                                </a>
                            </div>
                        @endif
                    </nav>

                    <!-- User Info -->
                    <div class="p-4 border-t border-gray-700">
                        <div class="flex items-center">
                            <div
                                class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-500 rounded-full flex items-center justify-center">
                                <span
                                    class="text-white font-semibold text-sm">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</span>
                            </div>
                            <div class="ml-3 flex-1">
                                <p class="text-sm font-medium text-white">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-gray-400">{{ ucfirst(auth()->user()->role) }}</p>
                            </div>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="text-gray-400 hover:text-white" title="Logout">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                        </path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- Main Content -->
            <div class="flex-1 flex flex-col overflow-hidden lg:ml-64">
                <!-- Top Header -->
                <header class="bg-gray-800 border-b border-gray-700 z-10">
                    <div class="flex items-center justify-between px-6 py-4">
                        <button id="openSidebar" class="lg:hidden text-gray-400 hover:text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                        <h1 class="text-xl font-semibold text-white">@yield('title', 'Dashboard')</h1>
                        @php
                            // Only show notifications from last 7 days
                            $unreadCount = auth()->user()->unreadNotifications()
                                ->where('created_at', '>=', now()->subDays(7))
                                ->count();
                            $recentNotifications = auth()->user()->notifications()
                                ->where('created_at', '>=', now()->subDays(7))
                                ->latest()
                                ->take(5)
                                ->get();
                        @endphp
                        <div class="hidden lg:flex items-center space-x-4">
                            <!-- Notification Bell -->
                            <div class="relative">
                                <button id="notificationToggle" type="button" class="relative p-2 rounded-full text-gray-300 hover:text-white hover:bg-gray-700 transition-colors focus:outline-none focus:ring-2 focus:ring-purple-500">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                    </svg>
                                    @if($unreadCount > 0)
                                        <span class="absolute top-0 right-0 inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-red-500 rounded-full">{{ $unreadCount > 9 ? '9+' : $unreadCount }}</span>
                                    @endif
                                </button>
                                
                                <!-- Notification Dropdown -->
                                <div id="notificationDropdown" class="hidden absolute right-0 mt-3 w-96 bg-gray-800 border border-gray-700 rounded-xl shadow-2xl z-50">
                                    <!-- Header -->
                                    <div class="flex items-center justify-between px-4 py-3 border-b border-gray-700 bg-gray-800">
                                        <h3 class="text-sm font-semibold text-white">Notifikasi</h3>
                                        @if($unreadCount > 0)
                                            <form action="{{ route('notifications.readAll') }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="text-xs px-3 py-1.5 rounded-lg bg-purple-600 hover:bg-purple-500 text-white font-medium transition-colors">
                                                    Tandai semua dibaca
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                    
                                    <!-- Notification List -->
                                    <div class="max-h-96 overflow-y-auto custom-scrollbar">
                                        @forelse($recentNotifications as $notification)
                                            @php 
                                                $data = $notification->data;
                                                $isUnread = is_null($notification->read_at);
                                            @endphp
                                            <div class="px-4 py-3 {{ $isUnread ? 'bg-gray-700/50' : 'bg-gray-800' }} hover:bg-gray-700 transition-colors border-b border-gray-700 last:border-0">
                                                <div class="flex items-start gap-3">
                                                    <!-- Icon -->
                                                    <div class="flex-shrink-0 pt-1">
                                                        <div class="w-10 h-10 rounded-full {{ $isUnread ? 'bg-purple-600' : 'bg-gray-700' }} flex items-center justify-center text-white text-sm font-semibold">
                                                            @if(isset($data['type']))
                                                                @if(str_contains($data['type'], 'baru'))
                                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                                                    </svg>
                                                                @elseif(str_contains($data['type'], 'status'))
                                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                                    </svg>
                                                                @elseif(str_contains($data['type'], 'ditugaskan') || str_contains($data['type'], 'diambil'))
                                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                                    </svg>
                                                                @else
                                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                                                    </svg>
                                                                @endif
                                                            @else
                                                                <span>N</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Content -->
                                                    <div class="flex-1 min-w-0">
                                                        <div class="flex items-start justify-between gap-2 mb-1">
                                                            <p class="text-sm font-semibold text-white line-clamp-1">{{ $data['title'] ?? 'Tiket' }}</p>
                                                            @if($isUnread)
                                                                <span class="flex-shrink-0 w-2 h-2 bg-purple-500 rounded-full mt-1.5"></span>
                                                            @endif
                                                        </div>
                                                        <p class="text-xs text-gray-300 line-clamp-2 mb-2">{{ $data['message'] ?? '' }}</p>
                                                        
                                                        <div class="flex items-center justify-between gap-2">
                                                            <div class="flex items-center gap-2 text-xs text-gray-400">
                                                                @if(!empty($data['ticket_number']))
                                                                    <span class="px-2 py-0.5 rounded bg-gray-700 text-purple-300 font-medium">{{ $data['ticket_number'] }}</span>
                                                                @endif
                                                                <span>{{ $notification->created_at->diffForHumans(null, true) }}</span>
                                                            </div>
                                                            
                                                            <div class="flex items-center gap-2">
                                                                @if(!empty($data['url']))
                                                                    <a href="{{ $data['url'] }}" class="text-xs text-purple-400 hover:text-purple-300 font-medium">
                                                                        Lihat
                                                                    </a>
                                                                @endif
                                                                @if($isUnread)
                                                                    <form action="{{ route('notifications.read', $notification) }}" method="POST" class="inline">
                                                                        @csrf
                                                                        <button type="submit" class="text-xs text-gray-400 hover:text-white">
                                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                                            </svg>
                                                                        </button>
                                                                    </form>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="px-4 py-8 text-center">
                                                <svg class="w-12 h-12 mx-auto text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                                </svg>
                                                <p class="text-sm text-gray-400">Belum ada notifikasi</p>
                                                <p class="text-[10px] text-gray-500 mt-1">7 hari terakhir</p>
                                            </div>
                                        @endforelse
                                    </div>
                                    
                                    <!-- Footer -->
                                    <div class="px-4 py-3 border-t border-gray-700 bg-gray-800">
                                        <div class="flex items-center justify-between">
                                            <a href="{{ route('notifications.index') }}" class="text-sm text-purple-400 hover:text-purple-300 font-semibold transition-colors">
                                                Lihat semua notifikasi
                                            </a>
                                            @if($unreadCount > 0)
                                                <span class="text-xs text-gray-400">{{ $unreadCount }} belum dibaca</span>
                                            @endif
                                        </div>
                                        <p class="text-[10px] text-gray-500 flex items-center gap-1 mt-2">
                                            <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span>Menampilkan 7 hari terakhir</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Time Display -->
                            <span class="text-sm text-gray-400">{{ now()->format('d M Y, H:i') }}</span>
                        </div>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="flex-1 overflow-y-auto bg-gray-900 custom-scrollbar">
                    <div class="p-6">
                        @if(session('success'))
                            <div class="mb-6 bg-green-900 border-l-4 border-green-500 text-green-100 p-4 rounded-lg"
                                role="alert">
                                <p class="font-medium">Sukses!</p>
                                <p>{{ session('success') }}</p>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="mb-6 bg-red-900 border-l-4 border-red-500 text-red-100 p-4 rounded-lg" role="alert">
                                <p class="font-medium">Error!</p>
                                <p>{{ session('error') }}</p>
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="mb-6 bg-red-900 border-l-4 border-red-500 text-red-100 p-4 rounded-lg" role="alert">
                                <p class="font-medium">Terjadi kesalahan:</p>
                                <ul class="mt-2 list-disc list-inside">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @yield('content')
                    </div>
                </main>
            </div>

            <!-- Sidebar Overlay for Mobile -->
            <div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden lg:hidden"></div>
        </div>

        <script>
            // Sidebar Toggle
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            const openSidebar = document.getElementById('openSidebar');
            const closeSidebar = document.getElementById('closeSidebar');

            openSidebar?.addEventListener('click', () => {
                sidebar.classList.remove('-translate-x-full');
                sidebarOverlay.classList.remove('hidden');
            });

            closeSidebar?.addEventListener('click', () => {
                sidebar.classList.add('-translate-x-full');
                sidebarOverlay.classList.add('hidden');
            });

            sidebarOverlay?.addEventListener('click', () => {
                sidebar.classList.add('-translate-x-full');
                sidebarOverlay.classList.add('hidden');
            });

            // Notification dropdown
            const notificationToggle = document.getElementById('notificationToggle');
            const notificationDropdown = document.getElementById('notificationDropdown');

            notificationToggle?.addEventListener('click', (e) => {
                e.stopPropagation();
                notificationDropdown?.classList.toggle('hidden');
            });

            document.addEventListener('click', (event) => {
                if (!notificationDropdown?.contains(event.target) && !notificationToggle?.contains(event.target)) {
                    notificationDropdown?.classList.add('hidden');
                }
            });
        </script>
    @else
        <main class="main-content">
            <div class="container mx-auto">
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-error">
                        {{ session('error') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-error">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>

        <footer class="footer">
            <div class="container">
                <p>&copy; {{ date('Y') }} IT Help Desk. All rights reserved.</p>
            </div>
        </footer>
    @endif
</body>

</html>