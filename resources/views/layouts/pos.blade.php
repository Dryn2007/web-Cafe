<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Cafe Laravel')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- Alpine.js + Plugin Persist (Agar keranjang tidak hilang saat pindah halaman) --}}
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/persist@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="bg-gray-50 font-sans" x-data="globalState">

    {{-- NAVBAR GLOBAL --}}
    <nav class="bg-white shadow-sm sticky top-0 z-50 border-b border-gray-200">
        <div class="max-w-3xl mx-auto px-4 py-3 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <a href="{{ route('order.index') }}"
                    class="text-xl font-black text-amber-600 tracking-tight flex items-center gap-2">
                    ☕ CAFE APP
                </a>
            </div>

            <div class="flex items-center gap-4">
                @auth
                    <span class="text-sm font-semibold text-gray-700 hidden sm:inline">Halo, {{ Auth::user()->name }}</span>
                    <a href="{{ route('order.history') }}" class="text-gray-500 hover:text-amber-600"
                        title="Riwayat Pesanan">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                            </path>
                        </svg>
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-gray-500 hover:text-red-500 transition" title="Logout">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                </path>
                            </svg>
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-bold text-amber-600">Login</a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- ISI HALAMAN BERUBAH-UBAH DISINI --}}
    @yield('content')

    {{-- Script Global untuk Keranjang (Persist di LocalStorage) --}}
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('globalState', () => ({
                // Keranjang disimpan di memori browser
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