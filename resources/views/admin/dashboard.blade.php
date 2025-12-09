@extends('layouts.admin')

@section('content')

    {{-- HEADER --}}
    <div class="mb-8">
        <h1 class="text-3xl font-black text-gray-800">📊 Dashboard</h1>
        <p class="text-gray-500 mt-1">Ringkasan aktivitas hari ini - {{ now()->translatedFormat('l, d F Y') }}</p>
    </div>

    {{-- STATS CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        {{-- Total Pesanan --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Pesanan Hari Ini</p>
                    <p class="text-4xl font-black text-gray-900 mt-2">{{ $totalOrders }}</p>
                </div>
                <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center">
                    <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                        </path>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Total Omset --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Omset Hari Ini</p>
                    <p class="text-3xl font-black text-green-600 mt-2">Rp {{ number_format($totalOmset, 0, ',', '.') }}</p>
                </div>
                <div class="w-14 h-14 bg-green-50 rounded-2xl flex items-center justify-center">
                    <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                        </path>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Stok Menipis --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Stok Menipis</p>
                    <p
                        class="text-4xl font-black {{ $lowStockIngredients->count() > 0 ? 'text-red-600' : 'text-gray-900' }} mt-2">
                        {{ $lowStockIngredients->count() }}</p>
                </div>
                <div
                    class="w-14 h-14 {{ $lowStockIngredients->count() > 0 ? 'bg-red-50' : 'bg-gray-50' }} rounded-2xl flex items-center justify-center">
                    <svg class="w-7 h-7 {{ $lowStockIngredients->count() > 0 ? 'text-red-600' : 'text-gray-400' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                        </path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- PESANAN MASUK (SIAP DIBUAT) --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
            <div>
                <h2 class="text-lg font-bold text-gray-800">🔥 Pesanan Masuk (Siap Dibuat)</h2>
                <p class="text-sm text-gray-500">Pesanan yang sudah dibayar dan menunggu diproses</p>
            </div>
            <span class="bg-amber-100 text-amber-700 px-3 py-1 rounded-full text-sm font-bold">{{ $orders->count() }}
                Antrian</span>
        </div>

        @if($orders->count() > 0)
            <div class="divide-y divide-gray-100">
                @foreach($orders as $order)
                    <div class="p-6 hover:bg-gray-50 transition">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <span
                                        class="bg-gray-900 text-white px-3 py-1 rounded-lg text-sm font-bold">#{{ $order->order_number }}</span>
                                    <span class="text-sm text-gray-500">{{ $order->created_at->diffForHumans() }}</span>
                                    <span
                                        class="bg-green-100 text-green-700 px-2 py-0.5 rounded text-xs font-semibold uppercase">{{ $order->payment_method }}</span>
                                </div>
                                <p class="text-sm text-gray-600 mb-2">
                                    <span class="font-semibold">{{ $order->user->name ?? 'Guest' }}</span>
                                </p>
                                {{-- Items --}}
                                <div class="flex flex-wrap gap-2">
                                    @foreach($order->items as $item)
                                        <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs font-medium">
                                            {{ $item->qty }}x {{ $item->product->name ?? 'Produk Dihapus' }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-lg font-black text-gray-900">Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                </p>
                                <button
                                    class="mt-2 bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-green-700 transition">
                                    ✓ Selesai
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="p-12 text-center">
                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-4xl">☕</span>
                </div>
                <h3 class="text-lg font-bold text-gray-800">Belum Ada Pesanan</h3>
                <p class="text-gray-500 text-sm">Pesanan yang masuk akan muncul di sini</p>
            </div>
        @endif
    </div>

    {{-- STOK MENIPIS WARNING --}}
    @if($lowStockIngredients->count() > 0)
        <div class="bg-red-50 rounded-2xl border border-red-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-red-200 bg-red-100">
                <h2 class="text-lg font-bold text-red-800 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                        </path>
                    </svg>
                    Peringatan Stok Menipis
                </h2>
            </div>
            <div class="p-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                    @foreach($lowStockIngredients as $ing)
                        <div class="flex items-center justify-between bg-white p-3 rounded-xl border border-red-200">
                            <div>
                                <p class="font-bold text-gray-800">{{ $ing->name }}</p>
                                <p class="text-sm text-red-600 font-semibold">Sisa: {{ number_format($ing->stock) }}
                                    {{ $ing->unit }}</p>
                            </div>
                            <a href="{{ route('admin.ingredients.edit', $ing->id) }}"
                                class="bg-red-600 text-white px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-red-700">
                                + Stok
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

@endsection