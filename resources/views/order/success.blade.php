@extends('layouts.pos')

@section('title', 'Yeay! Pesanan Berhasil')

@section('content')
<div class="min-h-[calc(100vh-60px)] flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        
        {{-- Confetti Animation --}}
        <div class="text-center mb-6">
            <div class="inline-flex items-center justify-center w-24 h-24 bg-gradient-to-br from-green-400 to-emerald-500 rounded-full shadow-2xl shadow-green-200 mb-4 animate-bounce">
                <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-black text-gray-900 mb-1">Yeay! Berhasil 🎉</h1>
            <p class="text-gray-500">Pesanan kamu udah masuk, tinggal tunggu aja!</p>
        </div>

        {{-- Ticket Card --}}
        <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100">
            {{-- Header --}}
            <div class="bg-gradient-to-r from-amber-500 to-orange-500 px-6 py-4 text-white text-center">
                <p class="text-sm font-medium opacity-90">Nomor Antrean</p>
                <h2 class="text-5xl font-black tracking-tight mt-1">{{ $order->queue_number }}</h2>
            </div>

            {{-- Divider --}}
            <div class="relative">
                <div class="absolute -left-4 top-0 w-8 h-8 bg-gradient-to-br from-orange-50 via-white to-amber-50 rounded-full"></div>
                <div class="absolute -right-4 top-0 w-8 h-8 bg-gradient-to-br from-orange-50 via-white to-amber-50 rounded-full"></div>
                <div class="border-t-2 border-dashed border-gray-200 mx-8"></div>
            </div>

            {{-- Details --}}
            <div class="p-6 space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-gray-500 text-sm">Nama</span>
                    <span class="font-bold text-gray-900">{{ $order->customer_name }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-500 text-sm">Pembayaran</span>
                    <span class="font-bold text-gray-900 uppercase flex items-center gap-1">
                        <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                        {{ $order->payment_method }}
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-500 text-sm">Waktu Order</span>
                    <span class="font-medium text-gray-700">{{ $order->created_at->format('H:i') }}</span>
                </div>
                
                <div class="pt-4 border-t border-gray-100">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600 font-medium">Total Bayar</span>
                        <span class="text-2xl font-black gradient-text">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            {{-- Status --}}
            <div class="bg-green-50 px-6 py-4 flex items-center gap-3 border-t border-green-100">
                <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <div>
                    <p class="font-bold text-green-800">Pembayaran Diterima</p>
                    <p class="text-green-600 text-sm">Pesanan sedang disiapkan</p>
                </div>
            </div>
        </div>

        {{-- Action Button --}}
        <a href="{{ route('order.index') }}"
            class="mt-6 block w-full text-center bg-gray-900 text-white py-4 rounded-2xl font-bold text-lg shadow-xl hover:bg-gray-800 hover:scale-[1.02] transition-all">
            Pesan Lagi yuk! ☕
        </a>

        <p class="text-center text-gray-400 text-sm mt-4">
            Screenshot halaman ini sebagai bukti ya!
        </p>
    </div>
</div>
@endsection