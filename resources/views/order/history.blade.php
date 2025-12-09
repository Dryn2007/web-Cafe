@extends('layouts.pos')

@section('title', 'Riwayat Pesanan')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-6">
    
    {{-- Header --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('order.index') }}" 
            class="p-2.5 bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md hover:scale-105 transition-all">
            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <div>
            <h1 class="text-xl font-black text-gray-800">Riwayat Pesanan 📜</h1>
            <p class="text-xs text-gray-500">Lihat semua pesanan kamu di sini</p>
        </div>
    </div>

    {{-- Orders List --}}
    <div class="space-y-4">
        @forelse($orders as $order)
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition-all">
                {{-- Header --}}
                <div class="p-5 border-b border-gray-100">
                    <div class="flex justify-between items-start">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-gradient-to-br from-amber-100 to-orange-100 rounded-2xl flex items-center justify-center">
                                <span class="text-xl">☕</span>
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="font-black text-gray-900">{{ $order->queue_number }}</span>
                                    <span class="px-2 py-0.5 text-[10px] font-bold rounded-full uppercase
                                        {{ $order->status == 'paid' ? 'bg-green-100 text-green-700' : '' }}
                                        {{ $order->status == 'completed' ? 'bg-blue-100 text-blue-700' : '' }}
                                        {{ $order->status == 'cancelled' ? 'bg-red-100 text-red-700' : '' }}
                                        {{ $order->status == 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}">
                                        {{ $order->status == 'paid' ? '✓ Dibayar' : '' }}
                                        {{ $order->status == 'completed' ? '✓ Selesai' : '' }}
                                        {{ $order->status == 'cancelled' ? '✗ Batal' : '' }}
                                        {{ $order->status == 'pending' ? '⏳ Pending' : '' }}
                                    </span>
                                </div>
                                <p class="text-xs text-gray-400 mt-0.5">{{ $order->created_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-black text-lg gradient-text">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                            <p class="text-xs text-gray-400 uppercase">{{ $order->payment_method }}</p>
                        </div>
                    </div>
                </div>

                {{-- Items --}}
                <div class="px-5 py-4 bg-gray-50">
                    <div class="flex flex-wrap gap-2">
                        @foreach($order->items as $item)
                            <span class="inline-flex items-center gap-1 bg-white px-3 py-1.5 rounded-full text-xs font-medium text-gray-700 border border-gray-200">
                                <span class="font-bold text-orange-500">{{ $item->qty }}x</span>
                                {{ $item->product->name ?? 'Produk Dihapus' }}
                            </span>
                        @endforeach
                    </div>
                </div>

                {{-- Footer --}}
                <div class="px-5 py-3 flex justify-end">
                    <a href="{{ route('order.show', $order->id) }}" 
                        class="text-sm font-semibold text-orange-500 hover:text-orange-600 flex items-center gap-1 group">
                        Lihat Detail
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
        @empty
            {{-- Empty State --}}
            <div class="text-center py-16">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-5xl">🧾</span>
                </div>
                <h3 class="text-xl font-bold text-gray-800">Belum Ada Pesanan</h3>
                <p class="text-gray-500 mt-1 mb-6">Yuk mulai pesan kopi pertamamu!</p>
                <a href="{{ route('order.index') }}" 
                    class="inline-flex items-center gap-2 bg-gradient-to-r from-orange-500 to-amber-500 text-white px-6 py-3 rounded-2xl font-bold shadow-lg shadow-orange-200 hover:shadow-orange-300 hover:scale-105 transition-all">
                    Pesan Sekarang ☕
                </a>
            </div>
        @endforelse
    </div>
</div>
@endsection