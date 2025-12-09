@extends('layouts.admin')

@section('content')

    {{-- HEADER --}}
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.products.index') }}"
            class="p-2 bg-white rounded-xl border border-gray-200 shadow-sm hover:bg-gray-50 transition">
            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-black text-gray-800">☕ Racik Menu Baru</h1>
            <p class="text-gray-500 text-sm">Tentukan nama, harga, dan komposisi resep.</p>
        </div>
    </div>

    {{-- Error Message --}}
    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 p-4 mb-6 rounded-xl">
            <ul class="list-disc pl-5 text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- FORM INPUT --}}
    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data"
        class="grid grid-cols-1 lg:grid-cols-3 gap-8" x-data="recipeApp()">
        @csrf

        {{-- BAGIAN 1: INFO PRODUK --}}
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="font-bold text-lg text-gray-800 mb-4 border-b pb-2 flex items-center gap-2">
                    <span class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center text-amber-600">📋</span>
                    Informasi Menu
                </h3>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Nama Menu</label>
                        <input type="text" name="name"
                            class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none transition"
                            placeholder="Contoh: Kopi Susu Aren" value="{{ old('name') }}" required>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Harga Jual (Rp)</label>
                        <div class="relative">
                            <span class="absolute left-4 top-3.5 text-gray-400 font-medium">Rp</span>
                            <input type="number" name="price"
                                class="w-full border border-gray-200 rounded-xl pl-12 pr-4 py-3 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none transition"
                                placeholder="25000" value="{{ old('price') }}" required>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Foto Menu</label>
                        <input type="file" name="image"
                            class="w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100 cursor-pointer border border-gray-200 rounded-xl">
                        <p class="text-xs text-gray-400 mt-2">Format: JPG, PNG. Max: 2MB.</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- BAGIAN 2: RESEP BUILDER --}}
        <div class="lg:col-span-2">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">

                <div class="flex justify-between items-center mb-4 border-b pb-4">
                    <h3 class="font-bold text-lg text-gray-800 flex items-center gap-2">
                        <span
                            class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center text-green-600">🧪</span>
                        Komposisi Resep
                    </h3>
                    <button type="button" @click="addRow()"
                        class="bg-green-100 text-green-700 px-4 py-2 rounded-xl text-sm font-bold hover:bg-green-200 transition flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Tambah Bahan
                    </button>
                </div>

                <div class="space-y-3">
                    {{-- Header Tabel --}}
                    <div class="grid grid-cols-12 gap-3 text-xs font-bold text-gray-500 uppercase tracking-wider px-2">
                        <div class="col-span-6">Nama Bahan Baku</div>
                        <div class="col-span-4">Jumlah Takaran</div>
                        <div class="col-span-2 text-center">Hapus</div>
                    </div>

                    {{-- Baris Input Dinamis --}}
                    <template x-for="(row, index) in rows" :key="index">
                        <div
                            class="grid grid-cols-12 gap-3 items-center bg-gray-50 p-3 rounded-xl border border-gray-200 hover:border-amber-300 transition">

                            {{-- Pilih Bahan --}}
                            <div class="col-span-6">
                                <select :name="`ingredients[${index}][id]`"
                                    class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                                    required>
                                    <option value="">-- Pilih Bahan --</option>
                                    @foreach($ingredients as $ing)
                                        <option value="{{ $ing->id }}">
                                            {{ $ing->name }} (Stok: {{ number_format($ing->stock) }} {{ $ing->unit }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Input Jumlah --}}
                            <div class="col-span-4">
                                <input type="number" :name="`ingredients[${index}][amount]`"
                                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-center focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                                    placeholder="0" min="1" required>
                            </div>

                            {{-- Tombol Hapus --}}
                            <div class="col-span-2 text-center">
                                <button type="button" @click="removeRow(index)"
                                    class="text-gray-400 hover:text-red-600 p-2 rounded-xl hover:bg-red-50 transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                        </path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>

                {{-- Info Box --}}
                <div class="mt-6 bg-blue-50 border border-blue-200 rounded-xl p-4 text-sm text-blue-800 flex gap-3">
                    <span class="text-xl">💡</span>
                    <div>
                        <strong>Cara Kerja:</strong> Setiap kali menu ini dipesan pelanggan, stok bahan baku di atas
                        akan berkurang otomatis sesuai takaran yang Anda isi.
                        <br><em class="text-blue-600">Contoh: Untuk Kopi Susu, masukkan "Biji Kopi" (18 gr) dan "Susu Cair"
                            (150 ml).</em>
                    </div>
                </div>
            </div>

            {{-- Tombol Simpan --}}
            <div class="mt-6 flex gap-3">
                <a href="{{ route('admin.products.index') }}"
                    class="px-6 py-3 bg-gray-100 text-gray-700 rounded-xl font-bold hover:bg-gray-200 transition">
                    Batal
                </a>
                <button type="submit"
                    class="flex-1 bg-gray-900 text-white px-8 py-3 rounded-xl font-bold text-lg shadow-lg hover:bg-black transition flex items-center justify-center gap-2">
                    💾 SIMPAN MENU
                </button>
            </div>
        </div>

    </form>

@endsection

@push('scripts')
    <script>
        function recipeApp() {
            return {
                rows: [{ id: '', amount: '' }],

                addRow() {
                    this.rows.push({ id: '', amount: '' });
                },

                removeRow(index) {
                    if (this.rows.length > 1) {
                        this.rows.splice(index, 1);
                    } else {
                        alert("Minimal harus ada 1 bahan baku untuk resep!");
                    }
                }
            }
        }
    </script>
@endpush