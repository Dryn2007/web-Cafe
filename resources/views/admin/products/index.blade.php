@extends('layouts.admin')

@section('content')

    {{-- HEADER HALAMAN --}}
    <div class="flex justify-between items-end mb-8">
        <div>
            <h1 class="text-3xl font-black text-gray-800">☕ Daftar Menu & Resep</h1>
            <p class="text-gray-500 mt-1">Kelola harga jual dan komposisi bahan baku produk.</p>
        </div>
        <a href="{{ route('admin.products.create') }}"
            class="bg-amber-600 text-white px-5 py-2.5 rounded-xl font-bold shadow-lg shadow-amber-600/30 hover:bg-amber-700 hover:scale-105 transition transform flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Buat Menu Baru
        </a>
    </div>

    {{-- NOTIFIKASI --}}
    @if(session('success'))
        <div class="bg-green-50 text-green-800 p-4 rounded-xl mb-6 border border-green-200 flex items-center gap-3 shadow-sm">
            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- GRID PRODUK --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($products as $product)
            <div
                class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition-shadow duration-300 group flex flex-col h-full">

                {{-- GAMBAR & HARGA --}}
                <div class="h-48 bg-gray-100 relative overflow-hidden">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    @else
                        <div class="w-full h-full flex flex-col items-center justify-center text-gray-300 bg-gray-50">
                            <span class="text-4xl">☕</span>
                            <span class="text-xs font-bold mt-2 uppercase tracking-wide">No Image</span>
                        </div>
                    @endif

                    {{-- Badge Harga --}}
                    <div
                        class="absolute top-3 right-3 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full text-sm font-black text-amber-600 shadow-sm border border-amber-100">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </div>
                </div>

                {{-- INFO PRODUK --}}
                <div class="p-5 flex-1 flex flex-col">
                    <h3 class="font-bold text-lg text-gray-900 mb-1 leading-tight">{{ $product->name }}</h3>

                    {{-- List Resep --}}
                    <div class="mt-4 bg-gray-50 rounded-lg p-3 border border-gray-100 flex-1">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z">
                                </path>
                            </svg>
                            Komposisi Resep
                        </p>
                        <ul class="space-y-1.5">
                            @foreach($product->ingredients as $ing)
                                <li
                                    class="flex justify-between text-xs text-gray-600 border-b border-gray-200 border-dashed pb-1 last:border-0 last:pb-0">
                                    <span>{{ $ing->name }}</span>
                                    <span class="font-mono font-bold text-gray-800">{{ $ing->pivot->amount_needed }}
                                        {{ $ing->unit }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                {{-- FOOTER CARD (HAPUS/EDIT) --}}
                <div class="px-5 py-3 border-t border-gray-100 bg-gray-50 flex justify-end gap-2">
                    {{-- Edit (Placeholder link) --}}
                    <button class="p-2 text-gray-400 hover:text-blue-600 transition" title="Edit Menu">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                            </path>
                        </svg>
                    </button>

                    {{-- Delete Action --}}
                    {{-- Note: Pastikan route destroy ada jika ingin fitur ini aktif --}}
                    {{-- <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST"
                        onsubmit="return confirm('Hapus menu ini?');">
                        @csrf @method('DELETE') --}}
                        <button class="p-2 text-gray-400 hover:text-red-600 transition" title="Hapus Menu"
                            onclick="alert('Fitur hapus belum diaktifkan di route')">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                </path>
                            </svg>
                        </button>
                        {{--
                    </form> --}}
                </div>
            </div>
        @empty
            {{-- EMPTY STATE --}}
            <div class="col-span-full flex flex-col items-center justify-center py-16 text-center">
                <div class="bg-gray-100 p-6 rounded-full mb-4">
                    <span class="text-6xl">📋</span>
                </div>
                <h3 class="text-xl font-bold text-gray-800">Belum Ada Menu</h3>
                <p class="text-gray-500 mb-6">Mulai racik menu kafe pertamamu sekarang.</p>
                <a href="{{ route('admin.products.create') }}"
                    class="bg-amber-600 text-white px-6 py-3 rounded-xl font-bold shadow hover:bg-amber-700">
                    + Buat Menu Pertama
                </a>
            </div>
        @endforelse
    </div>

@endsection