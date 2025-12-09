<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Berhasil</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen p-4">

    <div class="bg-white w-full max-w-md p-8 rounded-2xl shadow-xl text-center border-t-8 border-amber-500">
        <div class="mb-4 text-green-500 flex justify-center">
            <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>

        <h2 class="text-2xl font-bold text-gray-800 mb-1">Pesanan Diterima!</h2>
        <p class="text-gray-500 mb-6">Silakan menuju kasir untuk pembayaran.</p>

        <div class="bg-gray-50 rounded-lg p-6 mb-6 border border-gray-200 border-dashed">
            <p class="text-sm text-gray-500 uppercase tracking-widest mb-1">Nomor Antrean Anda</p>
            <h1 class="text-5xl font-black text-gray-800 tracking-tighter">{{ $order->queue_number }}</h1>
        </div>

        <div class="text-left text-sm text-gray-600 mb-6 space-y-2">
            <div class="flex justify-between">
                <span>Nama:</span>
                <span class="font-bold">{{ $order->customer_name }}</span>
            </div>
            <div class="flex justify-between border-t pt-2 mt-2">
                <span>Total Bayar:</span>
                <span class="font-bold text-lg text-amber-600">Rp
                    {{ number_format($order->total_price, 0, ',', '.') }}</span>
            </div>
        </div>

        <a href="{{ route('order.index') }}"
            class="block w-full bg-gray-800 text-white py-3 rounded-lg font-semibold hover:bg-gray-900 transition">
            Buat Pesanan Baru
        </a>
    </div>

</body>

</html>