@extends('layouts.admin')

@section('content')

    {{-- HEADER --}}
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.ingredients.index') }}"
            class="p-2 bg-white rounded-xl border border-gray-200 shadow-sm hover:bg-gray-50 transition">
            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-black text-gray-800">Tambah Bahan Baku</h1>
            <p class="text-gray-500 text-sm">Masukkan data stok bahan baru ke gudang</p>
        </div>
    </div>

    {{-- FORM --}}
    <div class="max-w-xl">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <form action="{{ route('admin.ingredients.store') }}" method="POST" class="space-y-5">
                @csrf

                {{-- Nama Bahan --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Nama Bahan</label>
                    <input type="text" name="name" value="{{ old('name') }}"
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none transition"
                        placeholder="Misal: Susu UHT" required>
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Stok & Satuan --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Jumlah Stok</label>
                        <input type="number" name="stock" value="{{ old('stock') }}"
                            class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none transition"
                            placeholder="1000" required>
                        @error('stock')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Satuan</label>
                        <select name="unit"
                            class="w-full border border-gray-200 rounded-xl px-4 py-3 bg-white focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none transition">
                            <option value="ml" {{ old('unit') == 'ml' ? 'selected' : '' }}>Mililiter (ml)</option>
                            <option value="gram" {{ old('unit') == 'gram' ? 'selected' : '' }}>Gram (gr)</option>
                            <option value="pcs" {{ old('unit') == 'pcs' ? 'selected' : '' }}>Pcs (Buah)</option>
                        </select>
                    </div>
                </div>

                {{-- Harga Beli --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Harga Beli per Satuan</label>
                    <div class="relative">
                        <span class="absolute left-4 top-3.5 text-gray-400 font-medium">Rp</span>
                        <input type="number" name="cost_per_unit" value="{{ old('cost_per_unit') }}"
                            class="w-full border border-gray-200 rounded-xl pl-12 pr-4 py-3 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none transition"
                            placeholder="Misal: 20" required>
                    </div>
                    <p class="text-xs text-gray-400 mt-2 bg-gray-50 p-2 rounded-lg">
                        💡 Contoh: Jika harga susu 1 Liter = Rp 20.000, maka harga per ml = 20.
                    </p>
                    @error('cost_per_unit')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tombol --}}
                <div class="flex gap-3 pt-4 border-t border-gray-100">
                    <a href="{{ route('admin.ingredients.index') }}"
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