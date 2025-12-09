@extends('layouts.admin')

@section('content')

    {{-- HEADER HALAMAN --}}
    <div class="flex justify-between items-end mb-8">
        <div>
            <h1 class="text-3xl font-black text-gray-800">📦 Gudang Bahan Baku</h1>
            <p class="text-gray-500 mt-1">Kelola stok bahan baku untuk produksi menu.</p>
        </div>
        <a href="{{ route('admin.ingredients.create') }}"
            class="bg-amber-600 text-white px-5 py-2.5 rounded-xl font-bold shadow-lg shadow-amber-600/30 hover:bg-amber-700 hover:scale-105 transition transform flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Tambah Bahan
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

    {{-- TABEL BAHAN BAKU --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Nama Bahan
                        </th>
                        <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Total Stok
                        </th>
                        <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Satuan</th>
                        <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Harga Beli
                            (HPP)</th>
                        <th class="text-center px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($ingredients as $item)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-amber-50 rounded-xl flex items-center justify-center">
                                        <span class="text-lg">🧂</span>
                                    </div>
                                    <span class="font-bold text-gray-800">{{ $item->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($item->stock < 500)
                                    <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm font-bold">
                                        {{ number_format($item->stock) }} <span class="text-xs">⚠️</span>
                                    </span>
                                @elseif($item->stock < 1000)
                                    <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-sm font-bold">
                                        {{ number_format($item->stock) }}
                                    </span>
                                @else
                                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-bold">
                                        {{ number_format($item->stock) }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-gray-500 uppercase text-sm font-medium">{{ $item->unit }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-gray-700 font-mono">Rp
                                    {{ number_format($item->cost_per_unit, 0, ',', '.') }}</span>
                                <span class="text-gray-400 text-sm">/ {{ $item->unit }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.ingredients.edit', $item->id) }}"
                                        class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition"
                                        title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.ingredients.destroy', $item->id) }}" method="POST"
                                        onsubmit="return confirm('Hapus bahan {{ $item->name }}?');" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition"
                                            title="Hapus">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                        <span class="text-3xl">📦</span>
                                    </div>
                                    <h3 class="text-lg font-bold text-gray-800">Belum Ada Bahan Baku</h3>
                                    <p class="text-gray-500 mb-4">Mulai tambahkan bahan baku untuk gudang Anda.</p>
                                    <a href="{{ route('admin.ingredients.create') }}"
                                        class="bg-amber-600 text-white px-4 py-2 rounded-lg font-bold hover:bg-amber-700">
                                        + Tambah Bahan Pertama
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection