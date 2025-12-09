@extends('layouts.pos')

@section('title', 'Checkout')

@section('content')
    <div class="max-w-2xl mx-auto px-4 py-6" x-data="checkoutLogic()">

        {{-- HEADER --}}
        <div class="flex items-center gap-2 mb-6">
            <a href="{{ route('order.index') }}" class="p-2 bg-white rounded-full border shadow-sm hover:bg-gray-50">⬅</a>
            <h1 class="text-xl font-bold text-gray-800">Konfirmasi Pesanan</h1>
        </div>

        {{-- 1. REVIEW PESANAN --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
            <h3 class="font-bold text-gray-700 mb-3 text-sm uppercase tracking-wider border-b pb-2">Rincian Menu</h3>
            <div class="space-y-3">
                <template x-for="(item, index) in cart" :key="item.id">
                    <div class="flex justify-between items-center">
                        <div class="flex gap-3 items-center">
                            <div class="bg-amber-50 text-amber-700 font-bold w-8 h-8 flex items-center justify-center rounded-lg text-sm"
                                x-text="item.qty + 'x'"></div>
                            <span class="text-gray-700 font-medium" x-text="item.name"></span>
                        </div>
                        <span class="text-gray-600 text-sm font-mono" x-text="formatRupiah(item.price * item.qty)"></span>
                    </div>
                </template>
            </div>
            <div class="border-t border-dashed border-gray-300 mt-4 pt-3 flex justify-between items-center">
                <span class="font-bold text-gray-800">Total Tagihan</span>
                <span class="font-black text-xl text-amber-600" x-text="formatRupiah(totalPrice)"></span>
            </div>
        </div>

        {{-- 2. PILIH METODE PEMBAYARAN --}}
        <div class="mb-32">
            <h3 class="font-bold text-gray-700 mb-3 text-sm uppercase tracking-wider">Metode Pembayaran</h3>
            <div class="grid grid-cols-2 gap-3">
                {{-- QRIS --}}
                <label class="cursor-pointer relative group">
                    <input type="radio" name="payment" value="qris" x-model="paymentMethod" class="peer sr-only">
                    <div
                        class="p-4 bg-white border-2 border-gray-100 rounded-xl hover:bg-gray-50 peer-checked:border-amber-500 peer-checked:bg-amber-50 peer-checked:ring-1 peer-checked:ring-amber-500 transition flex flex-col items-center justify-center gap-2 h-24">


                        [Image of QRIS logo]

                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/a2/Logo_QRIS.svg/1200px-Logo_QRIS.svg.png"
                            class="h-8 object-contain" alt="QRIS">
                        <span class="text-xs font-semibold text-gray-600">Scan QR</span>
                    </div>
                </label>
                {{-- E-Wallets --}}
                @foreach(['gopay', 'dana', 'shopeepay', 'ovo'] as $wallet)
                    <label class="cursor-pointer relative group">
                        <input type="radio" name="payment" value="{{ $wallet }}" x-model="paymentMethod" class="peer sr-only">
                        <div
                            class="p-4 bg-white border-2 border-gray-100 rounded-xl hover:bg-gray-50 peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:ring-1 peer-checked:ring-blue-500 transition flex flex-col items-center justify-center gap-2 h-24">
                            <span
                                class="font-bold text-base uppercase text-gray-700 group-hover:text-blue-600">{{ $wallet }}</span>
                        </div>
                    </label>
                @endforeach
            </div>
        </div>

        {{-- TOMBOL BAYAR (Sticky Footer) --}}
        <div class="fixed bottom-0 left-0 w-full bg-white p-4 border-t shadow-[0_-5px_10px_rgba(0,0,0,0.05)] z-40">
            <div class="max-w-2xl mx-auto">
                <button @click="handleMainButton()" :disabled="loading || !paymentMethod"
                    class="w-full bg-gray-900 text-white py-4 rounded-xl font-bold text-lg hover:bg-black active:scale-[0.98] transition disabled:opacity-50 disabled:cursor-not-allowed flex justify-center items-center gap-2">
                    <span x-show="!loading">BAYAR SEKARANG</span>
                    <span x-show="loading">Memproses...</span>
                </button>
            </div>
        </div>

        {{-- MODAL SIMULASI (Hanya dirender jika CONFIG TRUE) --}}
        @if(config('features.pembayaran_metode'))
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm" x-show="showModal"
                x-transition.opacity x-cloak>

                <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl overflow-hidden m-4"
                    @click.away="showModal = false">
                    <div class="bg-gray-50 px-6 py-4 border-b flex justify-between items-center">
                        <h3 class="font-bold text-gray-800">Selesaikan Pembayaran</h3>
                        <button @click="showModal = false"
                            class="text-gray-400 hover:text-red-500 font-bold text-xl">&times;</button>
                    </div>
                    <div class="p-6">

                        {{-- TAMPILAN QRIS --}}
                        <div x-show="paymentMethod === 'qris'" class="text-center">
                            <p class="text-sm text-gray-500 mb-4">Scan QR Code di bawah ini:</p>
                            <div class="bg-white p-3 inline-block border-2 border-gray-800 rounded-xl mb-4">


                                [Image of QR code for payment]

                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=CafeAppSimulasi"
                                    alt="QR Code" class="w-48 h-48">
                            </div>
                            <p class="font-bold text-2xl text-gray-900 mb-6" x-text="formatRupiah(totalPrice)"></p>
                            <button @click="submitOrder()"
                                class="w-full bg-amber-600 text-white py-3 rounded-lg font-bold hover:bg-amber-700 shadow-lg transition">
                                ✅ SAYA SUDAH BAYAR
                            </button>
                        </div>

                        {{-- TAMPILAN E-WALLET --}}
                        <div x-show="paymentMethod !== 'qris'">
                            <div class="flex flex-col items-center mb-6">
                                <div class="w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center mb-2 text-2xl">📱
                                </div>
                                <span class="text-xl font-black uppercase text-blue-600" x-text="paymentMethod"></span>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Nomor HP</label>
                                    <input type="number" x-model="walletPhone"
                                        class="w-full border-2 border-gray-200 rounded-lg p-3 font-mono" placeholder="0812...">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">PIN (6 Digit)</label>
                                    <input type="password" x-model="walletPin" maxlength="6"
                                        class="w-full border-2 border-gray-200 rounded-lg p-3 font-mono text-center tracking-[0.5em]"
                                        placeholder="••••••">
                                </div>
                            </div>
                            <button @click="validateWallet()"
                                class="mt-8 w-full bg-blue-600 text-white py-3 rounded-lg font-bold hover:bg-blue-700 shadow-lg transition">
                                BAYAR SEKARANG
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        @endif

    </div>

    @push('scripts')
        <script>
            function checkoutLogic() {
                return {
                    loading: false,
                    paymentMethod: null,

                    // State untuk Modal Simulasi
                    showModal: false,
                    walletPhone: '',
                    walletPin: '',

                    init() {
                        if (this.cart.length === 0) window.location.href = "{{ route('order.index') }}";
                    },

                    // ==========================================
                    // PERBAIKAN UTAMA ADA DI SINI
                    // ==========================================
                    handleMainButton() {
                        // 1. Cek Metode Bayar
                        if (!this.paymentMethod) return alert("Pilih metode pembayaran dulu!");

                        // 2. Cek Login
                        @guest
                            window.location.href = "{{ route('login') }}";
                            return;
                        @endguest

                        // 3. Logic Berdasarkan Config (Blade Logic didalam JS)
                        @if(config('features.pembayaran_metode'))
                            // --- JIKA CONFIG TRUE (Mode Simulasi) ---
                            // Reset input dan buka modal
                            this.walletPhone = '';
                            this.walletPin = '';
                            this.showModal = true;
                        @else
                            // --- JIKA CONFIG FALSE (Mode Langsung) ---
                            // Langsung kirim order ke server
                            this.submitOrder();
                        @endif
                            },

                    // Validasi Input di dalam Modal (Hanya dipakai jika Config True)
                    validateWallet() {
                        if (this.walletPhone.length < 8) return alert("Nomor HP tidak valid!");
                        if (this.walletPin.length !== 6) return alert("PIN harus 6 digit!");

                        // Jika valid, submit
                        this.submitOrder();
                    },

                    // Fungsi Kirim ke Backend (Fetch API)
                    async submitOrder() {
                        this.loading = true;
                        this.showModal = false; // Tutup modal jika terbuka

                        try {
                            let response = await fetch("{{ route('order.store') }}", {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    payment_method: this.paymentMethod,
                                    cart: this.cart
                                })
                            });

                            let result = await response.json();

                            if (response.ok) {
                                this.cart = [];
                                window.location.href = result.redirect_url;
                            } else {
                                alert("Gagal: " + result.message);
                                this.loading = false;
                            }
                        } catch (err) {
                            console.error(err);
                            alert("Terjadi kesalahan koneksi");
                            this.loading = false;
                        }
                    }
                }
            }
        </script>
    @endpush
@endsection