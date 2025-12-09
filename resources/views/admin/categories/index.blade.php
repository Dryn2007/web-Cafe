@extends('layouts.admin')

@section('content')

    {{-- HEADER HALAMAN --}}
    <div class="flex justify-between items-end mb-8">
        <div>
            <h1 class="text-3xl font-black text-gray-800">🏷️ Kategori Produk</h1>
            <p class="text-gray-500 mt-1">Kelola kategori untuk mengorganisir menu.</p>
        </div>
        <a href="{{ route('admin.categories.create') }}"
            class="bg-amber-600 text-white px-5 py-2.5 rounded-xl font-bold shadow-lg shadow-amber-600/30 hover:bg-amber-700 hover:scale-105 transition transform flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Tambah Kategori
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

    {{-- GRID KATEGORI --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($categories as $category)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition group">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center text-2xl">
                            {{ $category->icon ?? '📁' }}
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-800 text-lg">{{ $category->name }}</h3>
                            <p class="text-gray-400 text-sm">{{ $category->products_count }} produk</p>
                        </div>
                    </div>
                    <span class="bg-gray-100 text-gray-500 px-2 py-1 rounded-lg text-xs font-medium">
                        #{{ $category->sort_order }}
                    </span>
                </div>

                <div class="flex items-center gap-2 pt-4 border-t border-gray-100">
                    <a href="{{ route('admin.categories.edit', $category->id) }}"
                        class="flex-1 py-2 text-center text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition font-medium text-sm">
                        ✏️ Edit
                    </a>
                    <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST"
                        onsubmit="return confirm('Hapus kategori {{ $category->name }}? Produk di dalamnya akan menjadi tanpa kategori.');"
                        class="flex-1">
                        @csrf @method('DELETE')
                        <button type="submit"
                            class="w-full py-2 text-center text-gray-600 hover:text-red-600 hover:bg-red-50 rounded-lg transition font-medium text-sm">
                            🗑️ Hapus
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-gray-50 rounded-2xl p-12 text-center">
                <div class="text-6xl mb-4">📂</div>
                <h3 class="text-gray-600 font-bold text-lg">Belum ada kategori</h3>
                <p class="text-gray-400 mb-4">Buat kategori pertama untuk mengorganisir menu</p>
                <a href="{{ route('admin.categories.create') }}"
                    class="inline-flex items-center gap-2 bg-amber-600 text-white px-4 py-2 rounded-xl font-bold hover:bg-amber-700 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah Kategori
                </a>
            </div>
        @endforelse
    </div>

@endsection