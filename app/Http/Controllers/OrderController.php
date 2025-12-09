<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Product;
use App\Models\Order;

class OrderController extends Controller
{
    public function index()
    {
        $products = Product::where('is_available', true)->get();
        return view('order.index', compact('products'));
    }

    // UPDATE: Store dengan Auth & Payment Method
    public function store(Request $request)
    {
        $request->validate([
            // Nama diambil otomatis dari Auth, jadi tidak perlu divalidasi dari input
            'payment_method' => 'required|string|in:qris,gopay,dana,shopeepay,ovo',
            'cart'           => 'required|array',
            'cart.*.id'      => 'exists:products,id',
            'cart.*.qty'     => 'integer|min:1',
        ]);

        try {
            $order = DB::transaction(function () use ($request) {

                $user = Auth::user(); // Ambil user yang sedang login
                $totalPrice = 0;
                $cartItems = $request->cart;

                // 1. Cek Stok & Hitung Total
                foreach ($cartItems as $item) {
                    $product = Product::with('ingredients')->find($item['id']);
                    foreach ($product->ingredients as $ingredient) {
                        $totalNeeded = $ingredient->pivot->amount_needed * $item['qty'];
                        if ($ingredient->stock < $totalNeeded) {
                            throw new \Exception("Stok {$ingredient->name} habis!");
                        }
                    }
                    $totalPrice += $product->price * $item['qty'];
                }

                // 2. Generate Antrean
                $today = Carbon::today();
                $lastOrder = Order::whereDate('created_at', $today)->orderBy('id', 'desc')->first();
                $number = $lastOrder ? ((int)substr($lastOrder->queue_number, 2)) + 1 : 1;
                $queueCode = 'A-' . str_pad($number, 3, '0', STR_PAD_LEFT);

                // 3. Simpan Order (PENTING: Pakai user_id)
                $newOrder = Order::create([
                    'user_id'       => $user->id,          // ID User Login
                    'customer_name' => $user->name,        // Nama User Login
                    'queue_number'  => $queueCode,
                    'total_price'   => $totalPrice,
                    'payment_method' => $request->payment_method, // Simpan metode bayar
                    'status'        => 'paid',
                ]);

                // 4. Simpan Item & Kurangi Stok
                foreach ($cartItems as $item) {
                    $product = Product::find($item['id']);
                    $newOrder->items()->create([
                        'product_id' => $product->id,
                        'qty'        => $item['qty'],
                        'price'      => $product->price
                    ]);

                    foreach ($product->ingredients as $ingredient) {
                        $ingredient->decrement('stock', $ingredient->pivot->amount_needed * $item['qty']);
                    }
                }

                return $newOrder;
            });

            return response()->json([
                'status' => 'success',
                'redirect_url' => route('order.show', $order->id),
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 400);
        }
    }

    public function show($id)
    {
        // Pastikan user cuma bisa lihat order miliknya sendiri (Keamanan)
        $order = Order::with('items.product')
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('order.success', compact('order'));
    }

    // BARU: Halaman Riwayat Pesanan
    public function history()
    {
        $orders = Order::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('order.history', compact('orders'));
    }

    public function checkout()
    {
        return view('order.checkout');
    }
}
