@extends('layouts.pos')

@section('title', 'Pilih Menu')

@section('content')
    <div class="max-w-3xl mx-auto px-4 py-6 pb-28">

        {{-- Banner --}}
        <div class="bg-gradient-to-r from-amber-500 to-orange-600 rounded-xl p-6 text-white shadow-lg mb-8">
            <h2 class="text-2xl font-bold">Lapar? Haus?</h2>
            <p class="opacity-90">Pesan sekarang, bayar cashless lebih praktis!</p>
        </div>

        {{-- Grid Produk --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
            @foreach($products as $product)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden flex flex-col"
                    x-data="{ qty: 0 }">
                    <div class="h-32 bg-gray-100 relative">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-4xl">☕</div>
                        @endif
                    </div>

                    <div class="p-3 flex-1 flex flex-col">
                        <h3 class="font-bold text-gray-800 text-sm line-clamp-2">{{ $product->name }}</h3>
                        <p class="text-amber-600 font-bold text-sm mt-auto">Rp {{ number_format($product->price, 0, ',', '.') }}
                        </p>

                        <button @click="
                                    let item = cart.find(i => i.id === {{ $product->id }});
                                    if(item) { item.qty++; } 
                                    else { cart.push({ id: {{ $product->id }}, name: '{{ $product->name }}', price: {{ $product->price }}, qty: 1 }); }
                                "
                            class="mt-2 w-full bg-gray-900 text-white text-xs font-bold py-2 rounded-lg hover:bg-black active:scale-95 transition">
                            TAMBAH +
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- TOMBOL MENUJU PEMBAYARAN --}}
    <div class="fixed bottom-0 left-0 w-full bg-white border-t border-gray-200 p-4 shadow-[0_-5px_10px_rgba(0,0,0,0.05)]"
        x-show="cart.length > 0" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="transform translate-y-full" x-transition:enter-end="transform translate-y-0"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="transform translate-y-0"
        x-transition:leave-end="transform translate-y-full" x-cloak>
        <div class="max-w-3xl mx-auto flex justify-between items-center">
            <div>
                <p class="text-xs text-gray-500">Total Pesanan</p>
                <p class="text-lg font-black text-gray-900" x-text="formatRupiah(totalPrice)"></p>
            </div>
            <a href="{{ route('order.checkout') }}"
                class="bg-amber-600 text-white px-6 py-3 rounded-xl font-bold shadow-lg shadow-amber-200 hover:bg-amber-700 flex items-center gap-2">
                Lanjut Bayar <span x-text="'(' + totalQty + ')'"></span> ➔
            </a>
        </div>
    </div>
@endsection