@extends('layouts.pos')

@section('title', 'Menu - Kopiku')

@section('content')
    <div class="max-w-3xl mx-auto px-4 py-6 pb-32" x-data="menuSearch()">

        {{-- Hero Banner --}}
        <div
            class="relative overflow-hidden bg-gradient-to-r from-amber-500 via-orange-500 to-red-500 rounded-3xl p-6 text-white shadow-2xl shadow-orange-200 mb-6">
            <div class="absolute -right-8 -top-8 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
            <div class="absolute -left-8 -bottom-8 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
            <div class="relative">
                <span class="inline-block px-3 py-1 bg-white/20 rounded-full text-xs font-bold mb-3 backdrop-blur-sm">
                    🔥 PROMO HARI INI
                </span>
                <h2 class="text-2xl sm:text-3xl font-black leading-tight">Ngopi Dulu,<br>Baru Mikir! ☕</h2>
                <p class="text-white/80 mt-2 text-sm">Pesan sekarang, bayar cashless. Gak pake ribet!</p>
            </div>
            <div class="absolute right-4 bottom-4 text-6xl opacity-20">☕</div>
        </div>

        {{-- Search Bar --}}
        <div class="relative mb-4">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <input type="text" x-model="searchQuery" placeholder="Cari menu favoritmu..."
                class="w-full pl-12 pr-10 py-3.5 bg-white border border-gray-200 rounded-2xl text-sm font-medium placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent shadow-sm transition">
            <button x-show="searchQuery.length > 0" @click="searchQuery = ''"
                class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        {{-- Category Pills --}}
        <div class="flex gap-2 mb-6 overflow-x-auto pb-2 scrollbar-hide">
            <button @click="activeCategory = 'all'"
                :class="activeCategory === 'all' ? 'bg-gray-900 text-white shadow-lg' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50'"
                class="px-4 py-2 rounded-full text-sm font-bold whitespace-nowrap transition">
                🍵 Semua
            </button>
            @foreach($categories as $category)
                <button @click="activeCategory = '{{ $category->id }}'"
                    :class="activeCategory === '{{ $category->id }}' ? 'bg-gray-900 text-white shadow-lg' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50'"
                    class="px-4 py-2 rounded-full text-sm font-bold whitespace-nowrap transition">
                    {{ $category->icon ?? '📁' }} {{ $category->name }}
                </button>
            @endforeach
            @if($products->whereNull('category_id')->count() > 0)
                <button @click="activeCategory = 'uncategorized'"
                    :class="activeCategory === 'uncategorized' ? 'bg-gray-900 text-white shadow-lg' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50'"
                    class="px-4 py-2 rounded-full text-sm font-bold whitespace-nowrap transition">
                    📦 Lainnya
                </button>
            @endif
        </div>

        {{-- Search Result Info --}}
        <div x-show="searchQuery.length > 0" x-cloak class="mb-4">
            <p class="text-sm text-gray-500">
                Hasil pencarian untuk: <span class="font-bold text-gray-800" x-text="searchQuery"></span>
            </p>
        </div>

        {{-- Grid Produk --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
            @foreach($products as $product)
                @php
                    $isAvailable = $product->is_available && $product->hasEnoughStock();
                    $maxQty = $product->getMaxQuantityAvailable();
                @endphp
                <div x-show="isVisible('{{ addslashes($product->name) }}', '{{ $product->category_id ?? '' }}')"
                    x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    class="group bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden flex flex-col {{ $isAvailable ? 'hover:shadow-xl hover:shadow-orange-100 hover:-translate-y-1' : 'opacity-75' }} transition-all duration-300"
                    x-data="{ added: false }">

                    {{-- Image --}}
                    <div class="h-36 bg-gradient-to-br from-orange-50 to-amber-50 relative overflow-hidden">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}"
                                class="w-full h-full object-cover {{ $isAvailable ? 'group-hover:scale-110' : 'grayscale' }} transition-transform duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center {{ !$isAvailable ? 'grayscale' : '' }}">
                                <span
                                    class="text-5xl {{ $isAvailable ? 'group-hover:scale-125' : '' }} transition-transform duration-300">☕</span>
                            </div>
                        @endif

                        {{-- Price Badge --}}
                        {{-- <div class="absolute top-2 right-2 bg-white/90 backdrop-blur-sm px-2.5 py-1 rounded-full shadow-sm">
                            <span class="text-xs font-bold text-orange-600">Rp
                                {{ number_format($product->price / 1000, 0) }}K</span>
                        </div> --}}

                        {{-- Sold Out Overlay --}}
                        @if(!$isAvailable)
                            <div class="absolute inset-0 bg-black/50 flex items-center justify-center">
                                <div
                                    class="bg-red-500 text-white px-4 py-2 rounded-full font-black text-sm transform -rotate-12 shadow-lg">
                                    HABIS 😢
                                </div>
                            </div>
                        @elseif($maxQty > 0 && $maxQty <= 5)
                            {{-- Low Stock Warning --}}
                            <div class="absolute bottom-2 left-2 bg-amber-500 text-white px-2 py-1 rounded-full text-xs font-bold">
                                Sisa {{ $maxQty }} lagi!
                            </div>
                        @endif
                    </div>

                    {{-- Content --}}
                    <div class="p-4 flex-1 flex flex-col">
                        <h3 class="font-bold text-gray-800 text-sm leading-tight line-clamp-2 mb-1">{{ $product->name }}</h3>
                        <p class="text-orange-500 font-extrabold text-base mt-auto">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </p>

                        {{-- Add Button --}}
                        @if($isAvailable)
                            <button x-data="{ maxStock: {{ $maxQty }} }" @click="
                                                let item = cart.find(i => i.id === {{ $product->id }});
                                                let currentQty = item ? item.qty : 0;

                                                if (currentQty >= maxStock) {
                                                    Swal.fire({
                                                        icon: 'warning',
                                                        title: 'Stok Terbatas! 😅',
                                                        text: 'Maksimal ' + maxStock + ' untuk {{ $product->name }}',
                                                        confirmButtonColor: '#f97316',
                                                        confirmButtonText: 'Oke, Mengerti'
                                                    });
                                                    return;
                                                }

                                                if(item) { item.qty++; } 
                                                else { cart.push({ id: {{ $product->id }}, name: '{{ $product->name }}', price: {{ $product->price }}, qty: 1, maxStock: maxStock }); }
                                                added = true;
                                                setTimeout(() => added = false, 1000);
                                            "
                                class="mt-3 w-full py-2.5 rounded-xl font-bold text-sm transition-all duration-200 flex items-center justify-center gap-1"
                                :class="added ? 'bg-green-500 text-white scale-95' : 'bg-gray-900 text-white hover:bg-orange-500 active:scale-95'">
                                <span x-show="!added">TAMBAH +</span>
                                <span x-show="added" x-cloak>✓ DITAMBAH!</span>
                            </button>
                        @else
                            <button disabled
                                class="mt-3 w-full py-2.5 rounded-xl font-bold text-sm bg-gray-300 text-gray-500 cursor-not-allowed flex items-center justify-center gap-1">
                                <span>STOK HABIS</span>
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Empty State --}}
        @if($products->isEmpty())
            <div class="text-center py-16">
                <div class="w-24 h-24 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-5xl">🫗</span>
                </div>
                <h3 class="text-xl font-bold text-gray-800">Menu Lagi Kosong Nih</h3>
                <p class="text-gray-500 mt-1">Tunggu bentar ya, kita lagi siapin menu kece!</p>
            </div>
        @endif

        {{-- Empty Search Result --}}
        <div x-show="searchQuery.length > 0 && visibleCount === 0" x-cloak class="text-center py-12">
            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <span class="text-4xl">🔍</span>
            </div>
            <h3 class="text-lg font-bold text-gray-800">Menu tidak ditemukan</h3>
            <p class="text-gray-500 mt-1 text-sm">Tidak ada menu dengan kata "<span x-text="searchQuery"
                    class="font-bold"></span>"</p>
            <button @click="searchQuery = ''"
                class="mt-4 px-4 py-2 bg-orange-500 text-white rounded-full text-sm font-bold hover:bg-orange-600 transition">
                Lihat Semua Menu
            </button>
        </div>
    </div>

    {{-- Floating Cart Button --}}
    <div class="fixed bottom-0 left-0 w-full p-4 z-40" x-show="cart.length > 0"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="transform translate-y-full opacity-0"
        x-transition:enter-end="transform translate-y-0 opacity-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="transform translate-y-0 opacity-100"
        x-transition:leave-end="transform translate-y-full opacity-0" x-cloak>

        <div class="max-w-3xl mx-auto">
            <a href="{{ route('order.checkout') }}"
                class="flex items-center justify-between w-full bg-gradient-to-r from-gray-900 to-gray-800 text-white px-5 py-4 rounded-2xl shadow-2xl shadow-gray-900/30 hover:shadow-gray-900/50 transition-all group">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center relative">
                        <span class="text-xl">🛒</span>
                        <span
                            class="absolute -top-1 -right-1 w-5 h-5 bg-orange-500 rounded-full text-xs font-bold flex items-center justify-center"
                            x-text="totalQty"></span>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">Total Pesanan</p>
                        <p class="font-black text-lg" x-text="formatRupiah(totalPrice)"></p>
                    </div>
                </div>
                <div
                    class="flex items-center gap-2 bg-orange-500 px-4 py-2 rounded-xl font-bold group-hover:bg-orange-400 transition-colors">
                    Checkout
                    <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6">
                        </path>
                    </svg>
                </div>
            </a>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function menuSearch() {
            return {
                activeCategory: 'all',
                searchQuery: '',
                products: @json($products->map(fn($p) => ['id' => $p->id, 'name' => strtolower($p->name), 'category_id' => $p->category_id])),

                get visibleCount() {
                    return this.products.filter(p => {
                        const matchCategory = this.activeCategory === 'all' ||
                            this.activeCategory === String(p.category_id) ||
                            (this.activeCategory === 'uncategorized' && !p.category_id);
                        const matchSearch = this.searchQuery === '' ||
                            p.name.includes(this.searchQuery.toLowerCase());
                        return matchCategory && matchSearch;
                    }).length;
                },

                isVisible(productName, categoryId) {
                    const matchCategory = this.activeCategory === 'all' ||
                        this.activeCategory === String(categoryId) ||
                        (this.activeCategory === 'uncategorized' && !categoryId);
                    const matchSearch = this.searchQuery === '' ||
                        productName.toLowerCase().includes(this.searchQuery.toLowerCase());
                    return matchCategory && matchSearch;
                }
            }
        }
    </script>
@endpush