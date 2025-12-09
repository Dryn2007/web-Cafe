@extends('layouts.admin')

@section('content')

    {{-- HEADER --}}
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.expenses.index') }}"
            class="p-2 bg-white rounded-xl border border-gray-200 shadow-sm hover:bg-gray-50 transition">
            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-black text-gray-800">💸 Catat Pengeluaran</h1>
            <p class="text-gray-500 text-sm">Tambahkan catatan pengeluaran baru</p>
        </div>
    </div>

    {{-- FORM --}}
    <div class="max-w-2xl">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <form action="{{ route('admin.expenses.store') }}" method="POST" class="space-y-5"
                x-data="{ category: '{{ old('category') }}' }">
                @csrf

                {{-- Kategori --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Kategori Pengeluaran</label>
                    <select name="category" x-model="category"
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 bg-white focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition"
                        required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $key => $label)
                            <option value="{{ $key }}" {{ old('category') == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('category')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Deskripsi --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Deskripsi</label>
                    <input type="text" name="description" value="{{ old('description') }}"
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition"
                        placeholder="Misal: Pembelian Susu UHT 10 Liter" required>
                    @error('description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Jumlah & Tanggal --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Jumlah (Rp)</label>
                        <div class="relative">
                            <span class="absolute left-4 top-3.5 text-gray-400 font-medium">Rp</span>
                            <input type="number" name="amount" value="{{ old('amount') }}"
                                class="w-full border border-gray-200 rounded-xl pl-12 pr-4 py-3 focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition"
                                placeholder="100000" required>
                        </div>
                        @error('amount')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Tanggal</label>
                        <input type="date" name="expense_date" value="{{ old('expense_date', now()->format('Y-m-d')) }}"
                            class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition"
                            required>
                        @error('expense_date')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Bahan Baku (Jika kategori pembelian bahan) --}}
                <div x-show="category === 'ingredient_purchase'" x-transition
                    class="space-y-4 bg-amber-50 p-4 rounded-xl border border-amber-200">
                    <p class="text-sm font-bold text-amber-800 flex items-center gap-2">
                        <span>🧂</span> Detail Pembelian Bahan Baku
                    </p>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Pilih Bahan Baku</label>
                        <select name="ingredient_id"
                            class="w-full border border-gray-200 rounded-xl px-4 py-3 bg-white focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none transition">
                            <option value="">-- Pilih Bahan --</option>
                            @foreach($ingredients as $ing)
                                <option value="{{ $ing->id }}" {{ old('ingredient_id') == $ing->id ? 'selected' : '' }}>
                                    {{ $ing->name }} (Stok: {{ number_format($ing->stock) }} {{ $ing->unit }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Jumlah Beli</label>
                            <input type="number" name="quantity" value="{{ old('quantity') }}" step="0.01"
                                class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none transition"
                                placeholder="1000">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Satuan</label>
                            <select name="unit"
                                class="w-full border border-gray-200 rounded-xl px-4 py-3 bg-white focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none transition">
                                <option value="ml" {{ old('unit') == 'ml' ? 'selected' : '' }}>Mililiter (ml)</option>
                                <option value="gram" {{ old('unit') == 'gram' ? 'selected' : '' }}>Gram (gr)</option>
                                <option value="pcs" {{ old('unit') == 'pcs' ? 'selected' : '' }}>Pcs (Buah)</option>
                                <option value="liter" {{ old('unit') == 'liter' ? 'selected' : '' }}>Liter</option>
                                <option value="kg" {{ old('unit') == 'kg' ? 'selected' : '' }}>Kilogram (kg)</option>
                            </select>
                        </div>
                    </div>

                    <p class="text-xs text-amber-600 bg-amber-100 p-2 rounded-lg">
                        💡 Stok bahan baku akan otomatis bertambah saat pengeluaran ini disimpan.
                    </p>
                </div>

                {{-- Supplier --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Supplier / Vendor (Opsional)</label>
                    <input type="text" name="supplier" value="{{ old('supplier') }}"
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition"
                        placeholder="Misal: Toko ABC">
                </div>

                {{-- Catatan --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Catatan (Opsional)</label>
                    <textarea name="notes" rows="2"
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition resize-none"
                        placeholder="Catatan tambahan...">{{ old('notes') }}</textarea>
                </div>

                {{-- Tombol --}}
                <div class="flex gap-3 pt-4 border-t border-gray-100">
                    <a href="{{ route('admin.expenses.index') }}"
                        class="flex-1 text-center bg-gray-100 text-gray-700 py-3 rounded-xl font-bold hover:bg-gray-200 transition">
                        Batal
                    </a>
                    <button type="submit"
                        class="flex-1 bg-red-600 text-white py-3 rounded-xl font-bold hover:bg-red-700 shadow-lg shadow-red-600/30 transition">
                        💾 Simpan Pengeluaran
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection