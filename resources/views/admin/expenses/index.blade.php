@extends('layouts.admin')

@section('content')

    {{-- HEADER --}}
    <div class="flex justify-between items-end mb-8">
        <div>
            <h1 class="text-3xl font-black text-gray-800">💸 Catatan Pengeluaran</h1>
            <p class="text-gray-500 mt-1">Kelola semua pengeluaran bisnis kafe</p>
        </div>
        <a href="{{ route('admin.expenses.create') }}"
            class="bg-red-600 text-white px-5 py-2.5 rounded-xl font-bold shadow-lg shadow-red-600/30 hover:bg-red-700 hover:scale-105 transition transform flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Catat Pengeluaran
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

    {{-- FILTER --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-6">
        <form action="{{ route('admin.expenses.index') }}" method="GET" class="flex flex-wrap gap-4 items-end">
            <div>
                <label class="block text-xs font-bold text-gray-500 mb-1">Kategori</label>
                <select name="category" class="border border-gray-200 rounded-lg px-3 py-2 text-sm bg-white">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $key => $label)
                        <option value="{{ $key }}" {{ request('category') == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 mb-1">Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}"
                    class="border border-gray-200 rounded-lg px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 mb-1">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}"
                    class="border border-gray-200 rounded-lg px-3 py-2 text-sm">
            </div>
            <button type="submit" class="bg-gray-900 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-gray-800">
                🔍 Filter
            </button>
            <a href="{{ route('admin.expenses.index') }}" class="text-gray-500 hover:text-gray-700 text-sm font-medium">
                Reset
            </a>
        </form>
    </div>

    {{-- SUMMARY CARD --}}
    <div class="bg-gradient-to-r from-red-500 to-pink-500 rounded-2xl shadow-lg shadow-red-200 p-6 text-white mb-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-semibold text-red-100 uppercase tracking-wider">Total Pengeluaran (Filter Aktif)</p>
                <p class="text-3xl font-black mt-2">Rp {{ number_format($totalExpenses, 0, ',', '.') }}</p>
            </div>
            <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center">
                <span class="text-3xl">💸</span>
            </div>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Tanggal</th>
                        <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Kategori</th>
                        <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Deskripsi</th>
                        <th class="text-left px-6 py-4 text-xs font-bold text-gray-500 uppercase">Supplier</th>
                        <th class="text-right px-6 py-4 text-xs font-bold text-gray-500 uppercase">Jumlah</th>
                        <th class="text-center px-6 py-4 text-xs font-bold text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($expenses as $expense)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-600">{{ $expense->expense_date->format('d M Y') }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs font-medium">
                                    {{ $expense->category_label }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-semibold text-gray-800">{{ $expense->description }}</p>
                                @if($expense->ingredient)
                                    <p class="text-xs text-gray-500">
                                        Bahan: {{ $expense->ingredient->name }}
                                        @if($expense->quantity)
                                            ({{ number_format($expense->quantity) }} {{ $expense->unit }})
                                        @endif
                                    </p>
                                @endif
                                @if($expense->notes)
                                    <p class="text-xs text-gray-400 mt-1">{{ Str::limit($expense->notes, 50) }}</p>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-600">{{ $expense->supplier ?? '-' }}</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="font-bold text-red-600">Rp
                                    {{ number_format($expense->amount, 0, ',', '.') }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.expenses.edit', $expense->id) }}"
                                        class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.expenses.destroy', $expense->id) }}" method="POST"
                                        onsubmit="return confirm('Hapus pengeluaran ini?');" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition">
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
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <span class="text-3xl">📋</span>
                                </div>
                                <h3 class="text-lg font-bold text-gray-800">Belum Ada Pengeluaran</h3>
                                <p class="text-gray-500 text-sm">Catat pengeluaran pertama Anda</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($expenses->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $expenses->withQueryString()->links() }}
            </div>
        @endif
    </div>

@endsection