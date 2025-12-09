<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Ingredient;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dashboard()
    {
        // ====== STATISTIK KEUANGAN ======
        // Harian
        $todayOrders = Order::whereDate('created_at', today())->count();
        $todayOmset = Order::whereDate('created_at', today())->sum('total_price');

        // Bulanan
        $monthOrders = Order::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $monthOmset = Order::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_price');

        // Tahunan
        $yearOrders = Order::whereYear('created_at', now()->year)->count();
        $yearOmset = Order::whereYear('created_at', now()->year)->sum('total_price');

        // Stok menipis
        $lowStockIngredients = Ingredient::where('stock', '<', 1000)->get();

        // ====== HISTORY PESANAN ======
        // Ambil semua pesanan terbaru (untuk history)
        $orders = Order::with(['user', 'items.product'])
            ->orderBy('created_at', 'desc')
            ->take(20) // Ambil 20 pesanan terakhir
            ->get();

        // ====== DATA GRAFIK OMSET 7 HARI TERAKHIR ======
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $chartData[] = [
                'date' => $date->translatedFormat('D'),
                'total' => Order::whereDate('created_at', $date)->sum('total_price'),
            ];
        }

        return view('admin.dashboard', compact(
            'todayOrders',
            'todayOmset',
            'monthOrders',
            'monthOmset',
            'yearOrders',
            'yearOmset',
            'lowStockIngredients',
            'orders',
            'chartData'
        ));
    }
}
