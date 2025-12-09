<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Ingredient;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Data ringkas untuk dashboard
        $totalOrders = Order::whereDate('created_at', now())->count();
        $totalOmset = Order::whereDate('created_at', now())->sum('total_price');
        $lowStockIngredients = Ingredient::where('stock', '<', 1000)->get(); // Stok menipis

        // Ambil pesanan yang statusnya 'paid' (Siap dibuat)
        // Urutkan dari yang terbaru
        $orders = Order::where('status', 'paid')
            ->whereDate('created_at', now()) // Hanya hari ini
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.dashboard', compact('totalOrders', 'totalOmset', 'lowStockIngredients', 'orders'));
    }
}
