@extends('layouts.pos')

@section('title', 'Menu - Kopiku')

@section('content')
    <div class="max-w-3xl mx-auto px-4 py-6 pb-32">

        {{-- Hero Banner --}}
        <div class="relative overflow-hidden bg-gradient-to-r from-amber-500 via-orange-500 to-red-500 rounded-3xl p-6 text-white shadow-2xl shadow-orange-200 mb-8">
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

        {{-- Category Pills (Optional for future) --}}
        <div class="flex gap-2 mb-6 overflow-x-auto pb-2 scrollbar-hide">
            <button class="px-4 py-2 bg-gray-900 text-white rounded-full text-sm font-bold whitespace-nowrap shadow-lg">
                🍵 Semua Menu
            </button>
            <button class="px-4 py-2 bg-white text-gray-600 rounded-full text-sm font-medium whitespace-nowrap border border-gray-200 hover:bg-gray-50 transition">
                ☕ Kopi
            </button>
            <button class="px-4 py-2 bg-white text-gray-600 rounded-full text-sm font-medium whitespace-nowrap border border-gray-200 hover:bg-gray-50 transition">
                🧋 Non-Kopi
            </button>
            <button class="px-4 py-2 bg-white text-gray-600 rounded-full text-sm font-medium whitespace-nowrap border border-gray-200 hover:bg-gray-50 transition">
                🍰 Snack
            </button>
        </div>

        {{-- Grid Produk --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
            @foreach($products as $product)
                <div class="group bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden flex flex-col hover:shadow-xl hover:shadow-orange-100 hover:-translate-y-1 transition-all duration-300"
                    x-data="{ added: false }">
                    
                    {{-- Image --}}
                    <div class="h-36 bg-gradient-to-br from-orange-50 to-amber-50 relative overflow-hidden">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" 
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <span class="text-5xl group-hover:scale-125 transition-transform duration-300">☕</span>
                            </div>
                        @endif
                        
                        {{-- Price Badge --}}
                        <div class="absolute top-2 right-2 bg-white/90 backdrop-blur-sm px-2.5 py-1 rounded-full shadow-sm">
                            <span class="text-xs font-bold text-orange-600">Rp {{ number_format($product->price / 1000, 0) }}K</span>
                        </div>
                    </div>

                    {{-- Content --}}
                    <div class="p-4 flex-1 flex flex-col">
                        <h3 class="font-bold text-gray-800 text-sm leading-tight line-clamp-2 mb-1">{{ $product->name }}</h3>
                        <p class="text-orange-500 font-extrabold text-base mt-auto">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </p>

                        {{-- Add Button --}}
                        <button @click="
                                let item = cart.find(i => i.id === {{ $product->id }});
                                if(item) { item.qty++; } 
                                else { cart.push({ id: {{ $product->id }}, name: '{{ $product->name }}', price: {{ $product->price }}, qty: 1 }); }
                                added = true;
                                setTimeout(() => added = false, 1000);
                            "
                            class="mt-3 w-full py-2.5 rounded-xl font-bold text-sm transition-all duration-200 flex items-center justify-center gap-1"
                            :class="added ? 'bg-green-500 text-white scale-95' : 'bg-gray-900 text-white hover:bg-orange-500 active:scale-95'">
                            <span x-show="!added">TAMBAH +</span>
                            <span x-show="added" x-cloak>✓ DITAMBAH!</span>
                        </button>
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
    </div>

    {{-- Floating Cart Button --}}
    <div class="fixed bottom-0 left-0 w-full p-4 z-40"
        x-show="cart.length > 0" 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="transform translate-y-full opacity-0"
        x-transition:enter-end="transform translate-y-0 opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="transform translate-y-0 opacity-100"
        x-transition:leave-end="transform translate-y-full opacity-0"
        x-cloak>
        
        <div class="max-w-3xl mx-auto">
            <a href="{{ route('order.checkout') }}" 
                class="flex items-center justify-between w-full bg-gradient-to-r from-gray-900 to-gray-800 text-white px-5 py-4 rounded-2xl shadow-2xl shadow-gray-900/30 hover:shadow-gray-900/50 transition-all group">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center relative">
                        <span class="text-xl">🛒</span>
                        <span class="absolute -top-1 -right-1 w-5 h-5 bg-orange-500 rounded-full text-xs font-bold flex items-center justify-center" x-text="totalQty"></span>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">Total Pesanan</p>
                        <p class="font-black text-lg" x-text="formatRupiah(totalPrice)"></p>
                    </div>
                </div>
                <div class="flex items-center gap-2 bg-orange-500 px-4 py-2 rounded-xl font-bold group-hover:bg-orange-400 transition-colors">
                    Checkout
                    <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                    </svg>
                </div>
            </a>
        </div>
    </div>
@endsection