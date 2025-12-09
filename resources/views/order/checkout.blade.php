@extends('layouts.pos')

@section('title', 'Checkout - Kopiku')

@section('content')
    <div class="max-w-2xl mx-auto px-4 py-6 pb-32" x-data="checkoutLogic()">

        {{-- HEADER --}}
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('order.index') }}"
                class="p-2.5 bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md hover:scale-105 transition-all">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-xl font-black text-gray-800">Checkout 🧾</h1>
                <p class="text-xs text-gray-500">Cek pesanan kamu sebelum bayar</p>
            </div>
        </div>

        {{-- ORDER SUMMARY --}}
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden mb-6">
            <div class="bg-gradient-to-r from-orange-50 to-amber-50 px-5 py-3 border-b border-orange-100">
                <h3 class="font-bold text-gray-800 flex items-center gap-2">
                    <span class="text-lg">🛒</span> Pesanan Kamu
                </h3>
            </div>

            <div class="p-5 space-y-4">
                <template x-for="(item, index) in cart" :key="item.id">
                    <div class="flex items-center gap-4 group">
                        <div
                            class="w-12 h-12 bg-gradient-to-br from-orange-100 to-amber-100 rounded-2xl flex items-center justify-center shrink-0">
                            <span class="text-xl">☕</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-gray-800 truncate" x-text="item.name"></p>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-xs font-bold text-orange-500 bg-orange-50 px-2 py-0.5 rounded-full"
                                    x-text="item.qty + 'x'"></span>
                                <span class="text-xs text-gray-400" x-text="formatRupiah(item.price)"></span>
                            </div>
                        </div>
                        <p class="font-bold text-gray-900" x-text="formatRupiah(item.price * item.qty)"></p>
                        <button @click="removeFromCart(index)"
                            class="p-1.5 text-gray-300 hover:text-red-500 hover:bg-red-50 rounded-lg transition opacity-0 group-hover:opacity-100">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </template>
            </div>

            {{-- Total --}}
            <div class="px-5 py-4 bg-gray-50 border-t border-gray-100">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600 font-medium">Total Bayar</span>
                    <span class="text-2xl font-black gradient-text" x-text="formatRupiah(totalPrice)"></span>
                </div>
            </div>
        </div>

        {{-- PAYMENT METHODS --}}
        <div class="mb-6">
            <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                <span class="text-lg">💳</span> Pilih Pembayaran
            </h3>

            <div class="grid grid-cols-2 gap-3">
                {{-- QRIS --}}
                <label class="cursor-pointer group">
                    <input type="radio" name="payment" value="qris" x-model="paymentMethod" class="peer sr-only">
                    <div
                        class="p-4 bg-white border-2 border-gray-100 rounded-2xl hover:border-gray-200 peer-checked:border-orange-400 peer-checked:bg-gradient-to-br peer-checked:from-orange-50 peer-checked:to-amber-50 transition-all flex flex-col items-center justify-center gap-2 h-28 relative overflow-hidden group-hover:shadow-md">
                        <div
                            class="absolute inset-0 bg-gradient-to-br from-orange-500/5 to-amber-500/5 opacity-0 peer-checked:opacity-100 transition">
                        </div>
                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/a2/Logo_QRIS.svg/1200px-Logo_QRIS.svg.png"
                            class="h-8 object-contain relative" alt="QRIS">
                        <span class="text-xs font-bold text-gray-600 relative">Scan QR</span>
                        <div
                            class="absolute top-2 right-2 w-5 h-5 bg-orange-500 rounded-full opacity-0 peer-checked:opacity-100 flex items-center justify-center transition">
                            <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </div>
                </label>

                {{-- E-Wallets --}}
                @foreach(['gopay' => '💚', 'dana' => '💙', 'shopeepay' => '🧡', 'ovo' => '💜'] as $wallet => $emoji)
                    <label class="cursor-pointer group">
                        <input type="radio" name="payment" value="{{ $wallet }}" x-model="paymentMethod" class="peer sr-only">
                        <div
                            class="p-4 bg-white border-2 border-gray-100 rounded-2xl hover:border-gray-200 peer-checked:border-orange-400 peer-checked:bg-gradient-to-br peer-checked:from-orange-50 peer-checked:to-amber-50 transition-all flex flex-col items-center justify-center gap-2 h-28 relative overflow-hidden group-hover:shadow-md">
                            <span class="text-3xl">{{ $emoji }}</span>
                            <span class="font-bold text-sm uppercase text-gray-700">{{ $wallet }}</span>
                            <div
                                class="absolute top-2 right-2 w-5 h-5 bg-orange-500 rounded-full opacity-0 peer-checked:opacity-100 flex items-center justify-center transition">
                                <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>
                    </label>
                @endforeach
            </div>
        </div>

        {{-- PAY BUTTON --}}
        <div class="fixed bottom-0 left-0 w-full p-4 glass-effect border-t border-white/20 z-40">
            <div class="max-w-2xl mx-auto">
                <button @click="handleMainButton()" :disabled="loading || !paymentMethod"
                    class="w-full py-4 rounded-2xl font-bold text-lg transition-all flex justify-center items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
                    :class="paymentMethod ? 'bg-gradient-to-r from-orange-500 to-amber-500 text-white shadow-xl shadow-orange-200 hover:shadow-orange-300 hover:scale-[1.02] active:scale-[0.98]' : 'bg-gray-200 text-gray-400'">
                    <span x-show="!loading">
                        <span x-show="!paymentMethod">Pilih Pembayaran Dulu</span>
                        <span x-show="paymentMethod">BAYAR SEKARANG 🚀</span>
                    </span>
                    <span x-show="loading" class="flex items-center gap-2">
                        <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        Memproses...
                    </span>
                </button>
            </div>
        </div>

        {{-- MODAL SIMULASI --}}
        @if(config('features.pembayaran_metode'))
            <div class="fixed inset-0 z-50 flex items-center justify-center p-4" x-show="showModal" x-cloak>
                <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="showModal = false" x-transition.opacity>
                </div>

                <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl overflow-hidden relative z-10"
                    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90"
                    x-transition:enter-end="opacity-100 scale-100">

                    <div class="bg-gradient-to-r from-orange-500 to-amber-500 px-6 py-4 flex justify-between items-center">
                        <h3 class="font-bold text-white text-lg">Selesaikan Pembayaran</h3>
                        <button @click="showModal = false" class="text-white/80 hover:text-white text-2xl">&times;</button>
                    </div>

                    <div class="p-6">
                        {{-- QRIS View --}}
                        <div x-show="paymentMethod === 'qris'" class="text-center">
                            <p class="text-gray-500 mb-4">Scan QR Code di bawah ini:</p>
                            <div class="bg-white p-4 inline-block border-2 border-gray-900 rounded-2xl mb-4 shadow-lg">
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=CafeAppSimulasi"
                                    alt="QR Code" class="w-48 h-48">
                            </div>
                            <p class="font-black text-3xl text-gray-900 mb-6" x-text="formatRupiah(totalPrice)"></p>
                            <button @click="submitOrder()"
                                class="w-full bg-gradient-to-r from-green-500 to-emerald-500 text-white py-4 rounded-2xl font-bold text-lg shadow-lg shadow-green-200 hover:shadow-green-300 transition-all">
                                ✅ SAYA SUDAH BAYAR
                            </button>
                        </div>

                        {{-- E-Wallet View --}}
                        <div x-show="paymentMethod !== 'qris'">
                            <div class="flex flex-col items-center mb-6">
                                <div
                                    class="w-20 h-20 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-3xl flex items-center justify-center mb-3">
                                    <span class="text-4xl">📱</span>
                                </div>
                                <span class="text-2xl font-black uppercase gradient-text" x-text="paymentMethod"></span>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Nomor HP</label>
                                    <input type="number" x-model="walletPhone"
                                        class="w-full border-2 border-gray-200 rounded-2xl px-4 py-3 font-mono focus:border-orange-400 focus:ring-4 focus:ring-orange-100 outline-none transition"
                                        placeholder="0812...">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">PIN (6 Digit)</label>
                                    <input type="password" x-model="walletPin" maxlength="6"
                                        class="w-full border-2 border-gray-200 rounded-2xl px-4 py-3 font-mono text-center tracking-[0.5em] focus:border-orange-400 focus:ring-4 focus:ring-orange-100 outline-none transition"
                                        placeholder="••••••">
                                </div>
                            </div>
                            <button @click="validateWallet()"
                                class="mt-6 w-full bg-gradient-to-r from-blue-500 to-indigo-500 text-white py-4 rounded-2xl font-bold text-lg shadow-lg shadow-blue-200 hover:shadow-blue-300 transition-all">
                                BAYAR SEKARANG 💸
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
                    showModal: false,
                    walletPhone: '',
                    walletPin: '',

                    init() {
                        if (this.cart.length === 0) window.location.href = "{{ route('order.index') }}";
                    },

                    handleMainButton() {
                        if (!this.paymentMethod) return;

                        @guest
                            window.location.href = "{{ route('login') }}";
                            return;
                        @endguest

                        @if(config('features.pembayaran_metode'))
                            this.walletPhone = '';
                            this.walletPin = '';
                            this.showModal = true;
                        @else
                            this.submitOrder();
                        @endif
                            },

                    validateWallet() {
                        if (this.walletPhone.length < 8) return alert("Nomor HP tidak valid!");
                        if (this.walletPin.length !== 6) return alert("PIN harus 6 digit!");
                        this.submitOrder();
                    },

                    async submitOrder() {
                        this.loading = true;
                        this.showModal = false;

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