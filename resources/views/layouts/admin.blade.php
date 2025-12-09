<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Cafe System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-gray-50 font-sans antialiased">

    <div class="flex h-screen overflow-hidden">

        {{-- ======================== --}}
        {{-- SIDEBAR (KODE YANG BARU) --}}
        {{-- ======================== --}}
        <aside
            class="w-64 bg-gray-900 text-gray-400 flex flex-col shadow-2xl border-r border-gray-800 flex-shrink-0 transition-all duration-300">
            {{-- Brand --}}
            <div class="h-20 flex items-center gap-3 px-6 border-b border-gray-800 bg-gray-900">
                <div
                    class="w-8 h-8 bg-amber-500 rounded-lg flex items-center justify-center text-white font-bold text-lg">
                    C</div>
                <div>
                    <h1 class="text-white font-bold text-lg tracking-wide">CAFE ADMIN</h1>
                    <p class="text-xs text-gray-500">Management</p>
                </div>
            </div>

            {{-- Menu --}}
            <div class="flex-1 overflow-y-auto py-6 px-4 space-y-1">
                <p class="px-2 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Menu Utama</p>

                <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-amber-600 text-white shadow-lg' : 'hover:bg-gray-800 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                        </path>
                    </svg>
                    <span class="font-medium">Dashboard</span>
                </a>

                <div class="my-4 border-t border-gray-800"></div>
                <p class="px-2 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Master Data</p>

                <a href="{{ route('admin.ingredients.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('admin.ingredients.*') ? 'bg-amber-600 text-white shadow-lg' : 'hover:bg-gray-800 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    <span>Gudang Bahan</span>
                </a>

                <a href="{{ route('admin.products.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('admin.products.*') ? 'bg-amber-600 text-white shadow-lg' : 'hover:bg-gray-800 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                        </path>
                    </svg>
                    <span>Daftar Menu</span>
                </a>

                <a href="{{ route('admin.products.create') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all hover:bg-gray-800 hover:text-amber-400 text-sm ml-4 border-l border-gray-700">
                    <span>+ Racik Menu Baru</span>
                </a>
            </div>

            {{-- User --}}
            <div class="p-4 border-t border-gray-800 bg-gray-900">
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 rounded-full bg-gray-700 flex items-center justify-center text-white font-bold">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-white truncate">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500">Administrator</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="text-gray-400 hover:text-red-500"><svg class="w-6 h-6" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                </path>
                            </svg></button>
                    </form>
                </div>
            </div>
        </aside>

        {{-- ======================== --}}
        {{-- KONTEN UTAMA (KANAN) --}}
        {{-- ======================== --}}
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-8">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>

</html>