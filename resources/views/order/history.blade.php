<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pesanan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-4">

    <div class="max-w-3xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Riwayat Pesanan</h1>
            <a href="{{ route('order.index') }}" class="text-amber-600 font-semibold hover:underline">&larr; Kembali ke
                Menu</a>
        </div>

        <div class="space-y-4">
            @forelse($orders as $order)
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <span class="bg-amber-100 text-amber-800 text-xs font-bold px-2 py-1 rounded uppercase">
                                {{ $order->queue_number }}
                            </span>
                            <p class="text-gray-500 text-sm mt-1">{{ $order->created_at->format('d M Y, H:i') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-lg text-gray-800">Rp
                                {{ number_format($order->total_price, 0, ',', '.') }}</p>
                            <span
                                class="inline-block px-2 py-1 text-xs rounded font-bold uppercase
                                {{ $order->status == 'paid' ? 'bg-green-100 text-green-700' : ($order->status == 'completed' ? 'bg-blue-100 text-blue-700' : ($order->status == 'cancelled' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700')) }}">
                                {{ $order->status }}
                            </span>
                            <span class="text-xs text-gray-400 block mt-1 uppercase">{{ $order->payment_method }}</span>
                        </div>
                    </div>

                    {{-- Detail Item --}}
                    <div class="bg-gray-50 p-3 rounded-lg text-sm text-gray-600">
                        <p class="font-bold mb-2">Detail Menu:</p>
                        <ul class="list-disc pl-5">
                            @foreach($order->items as $item)
                                <li>
                                    {{ $item->qty }}x {{ $item->product->name ?? 'Produk Dihapus' }}
                                    (@ Rp {{ number_format($item->price) }})
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="mt-4 text-right">
                        <a href="{{ route('order.show', $order->id) }}" class="text-sm text-blue-600 hover:underline">Lihat
                            Struk Digital</a>
                    </div>
                </div>
            @empty
                <div class="text-center text-gray-500 py-10">Belum ada riwayat pesanan.</div>
            @endforelse
        </div>
    </div>

</body>

</html>