<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Cafe Laravel')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    {{-- Alpine.js + Plugin Persist --}}
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/persist@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Inter', system-ui, sans-serif; }
        .gradient-text {
            background: linear-gradient(135deg, #f59e0b 0%, #ef4444 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .glass-effect {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }
    </style>
</head>

<body class="bg-gradient-to-br from-orange-50 via-white to-amber-50 min-h-screen" x-data="globalState">

    {{-- NAVBAR MODERN --}}
    <nav class="glass-effect sticky top-0 z-50 border-b border-white/20 shadow-sm">
        <div class="max-w-3xl mx-auto px-4 py-3 flex justify-between items-center">
            <a href="{{ route('order.index') }}" class="flex items-center gap-2 group">
                <div class="w-10 h-10 bg-gradient-to-br from-amber-400 to-orange-500 rounded-2xl flex items-center justify-center shadow-lg shadow-orange-200 group-hover:scale-110 transition-transform">
                    <span class="text-white text-lg">☕</span>
                </div>
                <div class="hidden sm:block">
                    <span class="font-black text-lg gradient-text">KOPIKU</span>
                    <span class="block text-[10px] text-gray-400 -mt-1 font-medium">Ngopi Yuk!</span>
                </div>
            </a>

            <div class="flex items-center gap-2">
                @auth
                    <div class="hidden sm:flex items-center gap-2 bg-gray-100 px-3 py-1.5 rounded-full">
                        <div class="w-6 h-6 bg-gradient-to-br from-violet-400 to-purple-500 rounded-full flex items-center justify-center">
                            <span class="text-white text-xs font-bold">{{ substr(Auth::user()->name, 0, 1) }}</span>
                        </div>
                        <span class="text-sm font-semibold text-gray-700">{{ Auth::user()->name }}</span>
                    </div>
                    <a href="{{ route('order.history') }}" 
                        class="p-2.5 bg-white hover:bg-gray-50 rounded-xl border border-gray-100 shadow-sm transition-all hover:shadow-md hover:scale-105"
                        title="Riwayat">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" 
                            class="p-2.5 bg-white hover:bg-red-50 rounded-xl border border-gray-100 shadow-sm transition-all hover:shadow-md hover:scale-105 group"
                            title="Logout">
                            <svg class="w-5 h-5 text-gray-600 group-hover:text-red-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" 
                        class="px-4 py-2 bg-gradient-to-r from-amber-500 to-orange-500 text-white text-sm font-bold rounded-xl shadow-lg shadow-orange-200 hover:shadow-orange-300 hover:scale-105 transition-all">
                        Login ✨
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- ISI HALAMAN --}}
    @yield('content')

    {{-- Script Global --}}
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('globalState', () => ({
                cart: Alpine.$persist([]).as('shopping_cart'),

                get totalPrice() {
                    return this.cart.reduce((total, item) => total + (item.price * item.qty), 0);
                },
                get totalQty() {
                    return this.cart.reduce((total, item) => total + item.qty, 0);
                },
                formatRupiah(number) {
                    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number);
                },
                removeFromCart(index) {
                    this.cart.splice(index, 1);
                }
            }))
        })
    </script>
    @stack('scripts')
</body>

</html>