@extends('layouts.admin')

@section('content')

    {{-- HEADER --}}
    <div class="mb-8">
        <h1 class="text-3xl font-black text-gray-800">📊 Dashboard</h1>
        <p class="text-gray-500 mt-1">Ringkasan keuangan & aktivitas - {{ now()->translatedFormat('l, d F Y') }}</p>
    </div>

    {{-- KEUANGAN TABS --}}
    <div x-data="{ activeTab: 'daily' }" class="mb-8">
        {{-- Tab Navigation --}}
        <div class="flex gap-2 mb-6">
            <button @click="activeTab = 'daily'"
                :class="activeTab === 'daily' ? 'bg-amber-600 text-white shadow-lg' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50'"
                class="px-5 py-2.5 rounded-xl font-bold text-sm transition">
                📅 Harian
            </button>
            <button @click="activeTab = 'monthly'"
                :class="activeTab === 'monthly' ? 'bg-amber-600 text-white shadow-lg' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50'"
                class="px-5 py-2.5 rounded-xl font-bold text-sm transition">
                📆 Bulanan
            </button>
            <button @click="activeTab = 'yearly'"
                :class="activeTab === 'yearly' ? 'bg-amber-600 text-white shadow-lg' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50'"
                class="px-5 py-2.5 rounded-xl font-bold text-sm transition">
                🗓️ Tahunan
            </button>
        </div>

        {{-- DAILY STATS --}}
        <div x-show="activeTab === 'daily'" x-transition class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-lg shadow-blue-200 p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-blue-100 uppercase tracking-wider">Pesanan Hari Ini</p>
                        <p class="text-4xl font-black mt-2">{{ $todayOrders }}</p>
                        <p class="text-blue-200 text-sm mt-1">{{ now()->translatedFormat('d M Y') }}</p>
                    </div>
                    <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center">
                        <span class="text-3xl">📋</span>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl shadow-lg shadow-green-200 p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-green-100 uppercase tracking-wider">Omset Hari Ini</p>
                        <p class="text-3xl font-black mt-2">Rp {{ number_format($todayOmset, 0, ',', '.') }}</p>
                        <p class="text-green-200 text-sm mt-1">Total pendapatan</p>
                    </div>
                    <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center">
                        <span class="text-3xl">💰</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Stok Menipis</p>
                        <p class="text-4xl font-black {{ $lowStockIngredients->count() > 0 ? 'text-red-600' : 'text-gray-900' }} mt-2">
                            {{ $lowStockIngredients->count() }}
                        </p>
                        <p class="text-gray-400 text-sm mt-1">Perlu restock</p>
                    </div>
                    <div class="w-14 h-14 {{ $lowStockIngredients->count() > 0 ? 'bg-red-50' : 'bg-gray-50' }} rounded-2xl flex items-center justify-center">
                        <span class="text-3xl">⚠️</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- MONTHLY STATS --}}
        <div x-show="activeTab === 'monthly'" x-transition class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl shadow-lg shadow-purple-200 p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-purple-100 uppercase tracking-wider">Total Pesanan Bulan Ini</p>
                        <p class="text-4xl font-black mt-2">{{ $monthOrders }}</p>
                        <p class="text-purple-200 text-sm mt-1">{{ now()->translatedFormat('F Y') }}</p>
                    </div>
                    <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center">
                        <span class="text-3xl">📊</span>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-2xl shadow-lg shadow-indigo-200 p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-indigo-100 uppercase tracking-wider">Omset Bulan Ini</p>
                        <p class="text-3xl font-black mt-2">Rp {{ number_format($monthOmset, 0, ',', '.') }}</p>
                        <p class="text-indigo-200 text-sm mt-1">Total pendapatan bulanan</p>
                    </div>
                    <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center">
                        <span class="text-3xl">💵</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- YEARLY STATS --}}
        <div x-show="activeTab === 'yearly'" x-transition class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-gradient-to-br from-orange-500 to-red-500 rounded-2xl shadow-lg shadow-orange-200 p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-orange-100 uppercase tracking-wider">Total Pesanan Tahun Ini</p>
                        <p class="text-4xl font-black mt-2">{{ $yearOrders }}</p>
                        <p class="text-orange-200 text-sm mt-1">Tahun {{ now()->year }}</p>
                    </div>
                    <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center">
                        <span class="text-3xl">📈</span>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-teal-500 to-cyan-600 rounded-2xl shadow-lg shadow-teal-200 p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-teal-100 uppercase tracking-wider">Omset Tahun Ini</p>
                        <p class="text-3xl font-black mt-2">Rp {{ number_format($yearOmset, 0, ',', '.') }}</p>
                        <p class="text-teal-200 text-sm mt-1">Total pendapatan tahunan</p>
                    </div>
                    <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center">
                        <span class="text-3xl">🏆</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- GRAFIK OMSET 7 HARI TERAKHIR --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8">
        <h2 class="text-lg font-bold text-gray-800 mb-4">📈 Omset 7 Hari Terakhir</h2>
        <div class="flex items-end justify-between gap-2 h-48">
            @foreach($chartData as $data)
                @php
                    $maxOmset = collect($chartData)->max('total') ?: 1;
                    $height = $data['total'] > 0 ? ($data['total'] / $maxOmset) * 100 : 5;
                @endphp
                <div class="flex-1 flex flex-col items-center gap-2">
                    <div class="w-full bg-gray-100 rounded-t-lg relative flex items-end justify-center" style="height: 160px;">
                        <div class="w-full bg-gradient-to-t from-amber-500 to-amber-400 rounded-t-lg transition-all duration-500 hover:from-amber-600 hover:to-amber-500"
                            style="height: {{ $height }}%;"
                            title="Rp {{ number_format($data['total'], 0, ',', '.') }}">
                        </div>
                    </div>
                    <span class="text-xs font-bold text-gray-500">{{ $data['date'] }}</span>
                    <span class="text-[10px] text-gray-400">{{ number_format($data['total'] / 1000, 0) }}K</span>
                </div>
            @endforeach
        </div>
    </div>

    {{-- HISTORY PESANAN --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
            <div>
                <h2 class="text-lg font-bold text-gray-800">� History Pesanan</h2>
                <p class="text-sm text-gray-500">Daftar pesanan terbaru</p>
            </div>
            <span class="bg-gray-100 text-gray-600 px-3 py-1 rounded-full text-sm font-bold">
                {{ $orders->count() }} Pesanan
            </span>
        </div>

        @if($orders->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="text-left px-6 py-3 text-xs font-bold text-gray-500 uppercase">No. Order</th>
                            <th class="text-left px-6 py-3 text-xs font-bold text-gray-500 uppercase">Customer</th>
                            <th class="text-left px-6 py-3 text-xs font-bold text-gray-500 uppercase">Items</th>
                            <th class="text-left px-6 py-3 text-xs font-bold text-gray-500 uppercase">Total</th>
                            <th class="text-left px-6 py-3 text-xs font-bold text-gray-500 uppercase">Pembayaran</th>
                            <th class="text-left px-6 py-3 text-xs font-bold text-gray-500 uppercase">Waktu</th>
                            <th class="text-center px-6 py-3 text-xs font-bold text-gray-500 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($orders as $order)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <span class="bg-gray-900 text-white px-2 py-1 rounded text-xs font-bold">
                                        #{{ $order->order_number }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="font-semibold text-gray-800">{{ $order->user->name ?? 'Guest' }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap gap-1 max-w-xs">
                                        @foreach($order->items->take(3) as $item)
                                            <span class="bg-amber-50 text-amber-700 px-2 py-0.5 rounded text-xs font-medium">
                                                {{ $item->qty }}x {{ Str::limit($item->product->name ?? 'N/A', 10) }}
                                            </span>
                                        @endforeach
                                        @if($order->items->count() > 3)
                                            <span class="text-gray-400 text-xs">+{{ $order->items->count() - 3 }} lagi</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="font-bold text-gray-900">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="bg-blue-50 text-blue-700 px-2 py-1 rounded text-xs font-bold uppercase">
                                        {{ $order->payment_method }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm text-gray-500">{{ $order->created_at->format('d M, H:i') }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($order->status === 'paid')
                                        <span class="bg-green-100 text-green-700 px-2 py-1 rounded-full text-xs font-bold">
                                            ✓ Lunas
                                        </span>
                                    @elseif($order->status === 'pending')
                                        <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded-full text-xs font-bold">
                                            ⏳ Pending
                                        </span>
                                    @else
                                        <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded-full text-xs font-bold">
                                            {{ $order->status }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="p-12 text-center">
                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-4xl">☕</span>
                </div>
                <h3 class="text-lg font-bold text-gray-800">Belum Ada Pesanan</h3>
                <p class="text-gray-500 text-sm">Riwayat pesanan akan muncul di sini</p>
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