@extends('layouts.admin')

@section('content')

    {{-- HEADER --}}
    <div class="flex justify-between items-end mb-8">
        <div>
            <h1 class="text-3xl font-black text-gray-800">📊 Laporan Keuangan</h1>
            <p class="text-gray-500 mt-1">Analisis pendapatan, pengeluaran, dan profit bisnis</p>
        </div>
        <a href="{{ route('admin.expenses.create') }}"
            class="bg-red-600 text-white px-5 py-2.5 rounded-xl font-bold shadow-lg shadow-red-600/30 hover:bg-red-700 transition flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Catat Pengeluaran
        </a>
    </div>

    {{-- FILTER PERIODE --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-6">
        <form action="{{ route('admin.finance.index') }}" method="GET" class="flex flex-wrap gap-4 items-end">
            <div>
                <label class="block text-xs font-bold text-gray-500 mb-1">Periode</label>
                <select name="period" onchange="togglePeriodInputs()" id="periodSelect"
                    class="border border-gray-200 rounded-lg px-4 py-2 text-sm bg-white font-medium">
                    <option value="daily" {{ $period == 'daily' ? 'selected' : '' }}>📅 Harian</option>
                    <option value="monthly" {{ $period == 'monthly' ? 'selected' : '' }}>📆 Bulanan</option>
                    <option value="yearly" {{ $period == 'yearly' ? 'selected' : '' }}>🗓️ Tahunan</option>
                </select>
            </div>
            <div id="monthSelect" class="{{ $period == 'yearly' ? 'hidden' : '' }}">
                <label class="block text-xs font-bold text-gray-500 mb-1">Bulan</label>
                <select name="month" class="border border-gray-200 rounded-lg px-4 py-2 text-sm bg-white">
                    @foreach($months as $num => $name)
                        <option value="{{ $num }}" {{ $month == $num ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 mb-1">Tahun</label>
                <select name="year" class="border border-gray-200 rounded-lg px-4 py-2 text-sm bg-white">
                    @foreach($years as $y)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="bg-gray-900 text-white px-5 py-2 rounded-lg text-sm font-bold hover:bg-gray-800">
                🔍 Tampilkan
            </button>
        </form>
    </div>

    {{-- RINGKASAN UTAMA --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        {{-- Total Pendapatan --}}
        <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl shadow-lg p-5 text-white">
            <div class="flex items-center justify-between mb-2">
                <span class="text-green-100 text-xs font-bold uppercase">Pendapatan</span>
                <span class="text-2xl">💰</span>
            </div>
            <p class="text-2xl font-black">Rp {{ number_format($periodData['revenue'], 0, ',', '.') }}</p>
            <p class="text-green-200 text-xs mt-1">{{ $periodData['label'] }}</p>
        </div>

        {{-- Total Pengeluaran --}}
        <div class="bg-gradient-to-br from-red-500 to-pink-600 rounded-2xl shadow-lg p-5 text-white">
            <div class="flex items-center justify-between mb-2">
                <span class="text-red-100 text-xs font-bold uppercase">Pengeluaran</span>
                <span class="text-2xl">💸</span>
            </div>
            <p class="text-2xl font-black">Rp {{ number_format($periodData['expenses'], 0, ',', '.') }}</p>
            <p class="text-red-200 text-xs mt-1">{{ $periodData['label'] }}</p>
        </div>

        {{-- Laba Bersih --}}
        @if (config('features.laba_bersih'))
        <div
            class="bg-gradient-to-br {{ $periodData['profit'] >= 0 ? 'from-blue-500 to-indigo-600' : 'from-gray-600 to-gray-700' }} rounded-2xl shadow-lg p-5 text-white">
            <div class="flex items-center justify-between mb-2">
                <span class="text-blue-100 text-xs font-bold uppercase">Laba Bersih</span>
                <span class="text-2xl">{{ $periodData['profit'] >= 0 ? '📈' : '📉' }}</span>
            </div>
            <p class="text-2xl font-black">Rp {{ number_format($periodData['profit'], 0, ',', '.') }}</p>
            <p class="text-blue-200 text-xs mt-1">{{ $periodData['label'] }}</p>
        </div>
        @endif

        {{-- Total Order --}}
        <div class="bg-gradient-to-br from-purple-500 to-violet-600 rounded-2xl shadow-lg p-5 text-white">
            <div class="flex items-center justify-between mb-2">
                <span class="text-purple-100 text-xs font-bold uppercase">Total Order</span>
                <span class="text-2xl">🛒</span>
            </div>
            <p class="text-2xl font-black">{{ number_format($periodData['orders_count']) }}</p>
            <p class="text-purple-200 text-xs mt-1">Avg: Rp {{ number_format($periodData['avg_order_value'], 0, ',', '.') }}
            </p>
        </div>
    </div>

    {{-- MARGIN ANALYSIS --}}
    @if (config('features.analisis_margin'))
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                <span>📐</span> Analisis Margin Keuntungan
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                    <p class="text-xs text-gray-500 font-bold uppercase mb-1">Pendapatan Kotor</p>
                    <p class="text-xl font-black text-gray-900">Rp {{ number_format($marginAnalysis['revenue'], 0, ',', '.') }}
                    </p>
                </div>
                <div class="bg-amber-50 rounded-xl p-4 border border-amber-100">
                    <p class="text-xs text-amber-600 font-bold uppercase mb-1">HPP (Bahan Baku)</p>
                    <p class="text-xl font-black text-amber-700">Rp {{ number_format($marginAnalysis['hpp'], 0, ',', '.') }}</p>
                </div>
                <div class="bg-blue-50 rounded-xl p-4 border border-blue-100">
                    <p class="text-xs text-blue-600 font-bold uppercase mb-1">Laba Kotor</p>
                    <p class="text-xl font-black text-blue-700">Rp
                        {{ number_format($marginAnalysis['gross_profit'], 0, ',', '.') }}
                    </p>
                    <p class="text-xs text-blue-500 mt-1">Margin: {{ number_format($marginAnalysis['gross_margin'], 1) }}%</p>
                </div>
                <div
                    class="{{ $marginAnalysis['net_profit'] >= 0 ? 'bg-green-50 border-green-100' : 'bg-red-50 border-red-100' }} rounded-xl p-4 border">
                    <p
                        class="text-xs {{ $marginAnalysis['net_profit'] >= 0 ? 'text-green-600' : 'text-red-600' }} font-bold uppercase mb-1">
                        Laba Bersih</p>
                    <p class="text-xl font-black {{ $marginAnalysis['net_profit'] >= 0 ? 'text-green-700' : 'text-red-700' }}">
                        Rp {{ number_format($marginAnalysis['net_profit'], 0, ',', '.') }}</p>
                    <p class="text-xs {{ $marginAnalysis['net_profit'] >= 0 ? 'text-green-500' : 'text-red-500' }} mt-1">Margin:
                        {{ number_format($marginAnalysis['net_margin'], 1) }}%
                    </p>
                </div>
            </div>
        </div>
    @endif
    {{-- GRAFIK & BREAKDOWN --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        {{-- GRAFIK TREND --}}
        @if (config('features.grafik_keuangan'))
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-bold text-gray-800 mb-4">📈 Trend Pendapatan vs Pengeluaran</h2>
                <div class="h-64 flex items-end gap-1">
                    @foreach($chartData as $data)
                        @php
                            $maxVal = max(collect($chartData)->max('revenue'), collect($chartData)->max('expenses')) ?: 1;
                            $revHeight = $data['revenue'] > 0 ? ($data['revenue'] / $maxVal) * 100 : 2;
                            $expHeight = $data['expenses'] > 0 ? ($data['expenses'] / $maxVal) * 100 : 2;
                        @endphp
                        <div class="flex-1 flex flex-col items-center gap-1">
                            <div class="w-full flex gap-0.5 items-end" style="height: 200px;">
                                <div class="flex-1 bg-gradient-to-t from-green-500 to-green-400 rounded-t transition-all hover:from-green-600 hover:to-green-500"
                                    style="height: {{ $revHeight }}%;"
                                    title="Pendapatan: Rp {{ number_format($data['revenue'], 0, ',', '.') }}">
                                </div>
                                <div class="flex-1 bg-gradient-to-t from-red-400 to-red-300 rounded-t transition-all hover:from-red-500 hover:to-red-400"
                                    style="height: {{ $expHeight }}%;"
                                    title="Pengeluaran: Rp {{ number_format($data['expenses'], 0, ',', '.') }}">
                                </div>
                            </div>
                            <span class="text-[10px] font-bold text-gray-400">{{ $data['label'] }}</span>
                        </div>
                    @endforeach
                </div>
                <div class="flex justify-center gap-6 mt-4 text-xs">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 bg-green-500 rounded"></div>
                        <span class="text-gray-600">Pendapatan</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 bg-red-400 rounded"></div>
                        <span class="text-gray-600">Pengeluaran</span>
                    </div>
                </div>
            </div>
        @endif

        {{-- BREAKDOWN PENGELUARAN --}}
        @if (config('features.breakdown_pengeluaran'))
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-bold text-gray-800 mb-4">💸 Breakdown Pengeluaran</h2>
                @if($expensesByCategory->count() > 0)
                    <div class="space-y-3">
                        @foreach($expensesByCategory as $expense)
                            @php
                                $maxExpense = $expensesByCategory->max('total') ?: 1;
                                $width = ($expense->total / $maxExpense) * 100;
                            @endphp
                            <div>
                                <div class="flex justify-between text-sm mb-1">
                                    <span
                                        class="font-medium text-gray-700">{{ $categories[$expense->category] ?? $expense->category }}</span>
                                    <span class="font-bold text-red-600">Rp {{ number_format($expense->total, 0, ',', '.') }}</span>
                                </div>
                                <div class="w-full bg-gray-100 rounded-full h-2">
                                    <div class="bg-gradient-to-r from-red-400 to-pink-500 h-2 rounded-full"
                                        style="width: {{ $width }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-400">
                        <span class="text-4xl">📋</span>
                        <p class="mt-2">Belum ada pengeluaran</p>
                    </div>
                @endif
            </div>
        @endif
    </div>


    {{-- ANALISIS HPP PER PRODUK --}}
    @if (config('features.analisis_hpp'))
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                <span>🧮</span> Analisis HPP & Profit per Produk
            </h2>
            <p class="text-gray-500 text-sm mb-4">Perhitungan otomatis berdasarkan resep bahan baku yang digunakan</p>

            @if(count($hppByProduct) > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-200 bg-gray-50">
                                <th class="text-left py-3 px-4 text-xs font-bold text-gray-500 uppercase">Produk</th>
                                <th class="text-center py-3 px-4 text-xs font-bold text-gray-500 uppercase">Qty</th>
                                <th class="text-right py-3 px-4 text-xs font-bold text-gray-500 uppercase">Harga Jual</th>
                                <th class="text-right py-3 px-4 text-xs font-bold text-gray-500 uppercase">HPP/Unit</th>
                                <th class="text-right py-3 px-4 text-xs font-bold text-gray-500 uppercase">Profit/Unit</th>
                                <th class="text-center py-3 px-4 text-xs font-bold text-gray-500 uppercase">Margin</th>
                                <th class="text-right py-3 px-4 text-xs font-bold text-gray-500 uppercase">Total Profit</th>
                                <th class="text-center py-3 px-4 text-xs font-bold text-gray-500 uppercase">Detail</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($hppByProduct as $index => $product)
                                <tr class="hover:bg-gray-50" x-data="{ showDetail: false }">
                                    <td class="py-3 px-4">
                                        <span class="font-semibold text-gray-800">{{ $product['name'] }}</span>
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        <span class="bg-blue-50 text-blue-700 px-2 py-1 rounded font-bold text-sm">
                                            {{ number_format($product['qty_sold']) }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-right text-gray-700">
                                        Rp {{ number_format($product['price'], 0, ',', '.') }}
                                    </td>
                                    <td class="py-3 px-4 text-right">
                                        <span class="text-amber-600 font-semibold">
                                            Rp {{ number_format($product['hpp_per_unit'], 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-right">
                                        <span class="text-green-600 font-bold">
                                            Rp {{ number_format($product['profit_per_unit'], 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        <span
                                            class="px-2 py-1 rounded-full text-xs font-bold 
                                                                                                                                {{ $product['margin_percent'] >= 50 ? 'bg-green-100 text-green-700' : ($product['margin_percent'] >= 30 ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                                            {{ number_format($product['margin_percent'], 1) }}%
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-right font-black text-green-700">
                                        Rp {{ number_format($product['total_profit'], 0, ',', '.') }}
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        <button @click="showDetail = !showDetail"
                                            class="text-blue-500 hover:text-blue-700 text-xs font-bold">
                                            <span x-show="!showDetail">📋 Lihat</span>
                                            <span x-show="showDetail">✕ Tutup</span>
                                        </button>
                                    </td>
                                </tr>
                                {{-- Detail Ingredients Row --}}
                                <tr x-show="showDetail" x-transition class="bg-amber-50">
                                    <td colspan="8" class="py-3 px-4">
                                        <div class="text-sm">
                                            <p class="font-bold text-amber-800 mb-2">📦 Rincian Bahan Baku untuk 1
                                                {{ $product['name'] }}:
                                            </p>
                                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2">
                                                @foreach($product['ingredients'] as $ing)
                                                    <div
                                                        class="bg-white p-2 rounded-lg border border-amber-200 flex justify-between items-center">
                                                        <div>
                                                            <span class="font-medium text-gray-700">{{ $ing['name'] }}</span>
                                                            <p class="text-xs text-gray-500">{{ $ing['amount'] }} {{ $ing['unit'] }} × Rp
                                                                {{ number_format($ing['cost_per_unit'], 0, ',', '.') }}
                                                            </p>
                                                        </div>
                                                        <span class="font-bold text-amber-600">Rp
                                                            {{ number_format($ing['total_cost'], 0, ',', '.') }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <div class="mt-2 pt-2 border-t border-amber-200 flex justify-between">
                                                <span class="font-bold text-amber-800">Total HPP per Unit:</span>
                                                <span class="font-black text-amber-700">Rp
                                                    {{ number_format($product['hpp_per_unit'], 0, ',', '.') }}</span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-100 border-t-2 border-gray-300">
                            <tr>
                                <td class="py-3 px-4 font-bold text-gray-800">TOTAL</td>
                                <td class="py-3 px-4 text-center font-bold">
                                    {{ number_format(collect($hppByProduct)->sum('qty_sold')) }}
                                </td>
                                <td class="py-3 px-4"></td>
                                <td class="py-3 px-4 text-right font-bold text-amber-700">Rp {{ number_format($hpp, 0, ',', '.') }}
                                </td>
                                <td class="py-3 px-4"></td>
                                <td class="py-3 px-4"></td>
                                <td class="py-3 px-4 text-right font-black text-green-700 text-lg">
                                    Rp {{ number_format(collect($hppByProduct)->sum('total_profit'), 0, ',', '.') }}
                                </td>
                                <td class="py-3 px-4"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @else
                <div class="text-center py-12 text-gray-400">
                    <span class="text-5xl">📊</span>
                    <p class="mt-3">Belum ada data penjualan untuk periode ini</p>
                </div>
            @endif
        </div>
    @endif

    {{-- TOP PRODUK --}}
    @if (config('features.top_produk'))
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4">🏆 Top 10 Produk Terlaris</h2>
            @if($revenueByProduct->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-100">
                                <th class="text-left py-3 text-xs font-bold text-gray-500 uppercase">#</th>
                                <th class="text-left py-3 text-xs font-bold text-gray-500 uppercase">Produk</th>
                                <th class="text-center py-3 text-xs font-bold text-gray-500 uppercase">Qty Terjual</th>
                                <th class="text-right py-3 text-xs font-bold text-gray-500 uppercase">Total Revenue</th>
                                <th class="text-right py-3 text-xs font-bold text-gray-500 uppercase">Kontribusi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($revenueByProduct as $index => $product)
                                <tr class="hover:bg-gray-50">
                                    <td class="py-3">
                                        @if($index < 3)
                                            <span class="text-lg">{{ ['🥇', '🥈', '🥉'][$index] }}</span>
                                        @else
                                            <span class="text-gray-400 font-bold">{{ $index + 1 }}</span>
                                        @endif
                                    </td>
                                    <td class="py-3 font-semibold text-gray-800">{{ $product->name }}</td>
                                    <td class="py-3 text-center">
                                        <span class="bg-blue-50 text-blue-700 px-2 py-1 rounded font-bold text-sm">
                                            {{ number_format($product->total_qty) }}
                                        </span>
                                    </td>
                                    <td class="py-3 text-right font-bold text-green-600">
                                        Rp {{ number_format($product->total_revenue, 0, ',', '.') }}
                                    </td>
                                    <td class="py-3 text-right text-sm text-gray-500">
                                        {{ $periodData['revenue'] > 0 ? number_format(($product->total_revenue / $periodData['revenue']) * 100, 1) : 0 }}%
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-8 text-gray-400">
                    <span class="text-4xl">📋</span>
                    <p class="mt-2">Belum ada data penjualan</p>
                </div>
            @endif
        </div>
    @endif


    {{-- RINGKASAN ALL TIME --}}
    @if (config('features.ringkasan_all_time'))
        <div class="bg-gradient-to-r from-gray-800 to-gray-900 rounded-2xl shadow-lg p-6 text-white">
            <h2 class="text-lg font-bold mb-4 flex items-center gap-2">
                <span>🏆</span> Ringkasan Sepanjang Masa
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <p class="text-gray-400 text-xs uppercase font-bold mb-1">Total Pendapatan</p>
                    <p class="text-2xl font-black text-green-400">Rp {{ number_format($totalAllTimeRevenue, 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs uppercase font-bold mb-1">Total Pengeluaran</p>
                    <p class="text-2xl font-black text-red-400">Rp {{ number_format($totalAllTimeExpenses, 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs uppercase font-bold mb-1">Total Profit</p>
                    <p class="text-2xl font-black {{ $totalAllTimeProfit >= 0 ? 'text-blue-400' : 'text-red-400' }}">
                        Rp {{ number_format($totalAllTimeProfit, 0, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('scripts')
    <script>
        function togglePeriodInputs() {
            const period = document.getElementById('periodSelect').value;
            const monthSelect = document.getElementById('monthSelect');

            if (period === 'yearly') {
                monthSelect.classList.add('hidden');
            } else {
                monthSelect.classList.remove('hidden');
            }
        }
    </script>
@endpush