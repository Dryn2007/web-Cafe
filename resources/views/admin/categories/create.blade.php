@extends('layouts.admin')

@section('content')

    {{-- HEADER --}}
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.categories.index') }}"
            class="p-2 bg-white rounded-xl border border-gray-200 shadow-sm hover:bg-gray-50 transition">
            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-black text-gray-800">Tambah Kategori</h1>
            <p class="text-gray-500 text-sm">Buat kategori baru untuk mengorganisir produk</p>
        </div>
    </div>

    {{-- FORM --}}
    <div class="max-w-xl">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-5">
                @csrf

                {{-- Nama Kategori --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Nama Kategori</label>
                    <input type="text" name="name" value="{{ old('name') }}"
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none transition"
                        placeholder="Misal: Kopi, Minuman Dingin, Makanan" required>
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Icon --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Icon (Emoji)</label>
                    <input type="text" name="icon" value="{{ old('icon') }}"
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none transition"
                        placeholder="Misal: ☕ 🧊 🍕">
                    <p class="text-xs text-gray-400 mt-2">
                        💡 Gunakan emoji untuk membuat kategori lebih menarik. Bisa dikosongkan.
                    </p>
                    @error('icon')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Urutan --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Urutan Tampil</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" min="0"
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none transition"
                        placeholder="0">
                    <p class="text-xs text-gray-400 mt-2">
                        💡 Angka lebih kecil akan ditampilkan lebih dulu.
                    </p>
                    @error('sort_order')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tombol --}}
                <div class="flex gap-3 pt-4 border-t border-gray-100">
                    <a href="{{ route('admin.categories.index') }}"
                        class="flex-1 text-center bg-gray-100 text-gray-700 py-3 rounded-xl font-bold hover:bg-gray-200 transition">
                        Batal
                    </a>
                    <button type="submit"
                        class="flex-1 bg-amber-600 text-white py-3 rounded-xl font-bold hover:bg-amber-700 shadow-lg shadow-amber-600/30 transition">
                        💾 Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection