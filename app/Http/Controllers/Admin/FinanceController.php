<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Expense;
use App\Models\Product;
use App\Models\Ingredient;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FinanceController extends Controller
{
    public function index(Request $request)
    {
        // Period filter
        $period = $request->period ?? 'monthly';
        $year = $request->year ?? now()->year;
        $month = $request->month ?? now()->month;

        // ====== RINGKASAN KESELURUHAN ======
        $totalAllTimeRevenue = Order::sum('total_price');
        $totalAllTimeExpenses = Expense::sum('amount');
        $totalAllTimeProfit = $totalAllTimeRevenue - $totalAllTimeExpenses;

        // ====== DATA PERIODE TERPILIH ======
        $periodData = $this->getPeriodData($period, $year, $month);

        // ====== BREAKDOWN PENDAPATAN ======
        $revenueByProduct = Order::join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->when($period === 'daily', function ($q) {
                return $q->whereDate('orders.created_at', today());
            })
            ->when($period === 'monthly', function ($q) use ($year, $month) {
                return $q->whereMonth('orders.created_at', $month)
                    ->whereYear('orders.created_at', $year);
            })
            ->when($period === 'yearly', function ($q) use ($year) {
                return $q->whereYear('orders.created_at', $year);
            })
            ->select('products.name', DB::raw('SUM(order_items.qty) as total_qty'), DB::raw('SUM(order_items.qty * order_items.price) as total_revenue'))
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_revenue')
            ->limit(10)
            ->get();

        // ====== BREAKDOWN PENGELUARAN ======
        $expensesByCategory = Expense::when($period === 'daily', function ($q) {
            return $q->whereDate('expense_date', today());
        })
            ->when($period === 'monthly', function ($q) use ($year, $month) {
                return $q->whereMonth('expense_date', $month)
                    ->whereYear('expense_date', $year);
            })
            ->when($period === 'yearly', function ($q) use ($year) {
                return $q->whereYear('expense_date', $year);
            })
            ->select('category', DB::raw('SUM(amount) as total'))
            ->groupBy('category')
            ->orderByDesc('total')
            ->get();

        // ====== GRAFIK TREND ======
        $chartData = $this->getChartData($period, $year, $month);

        // ====== HPP (Harga Pokok Penjualan) ======
        $hppData = $this->calculateHPP($period, $year, $month);
        $hpp = $hppData['total'];
        $hppByProduct = $hppData['by_product'];

        // ====== MARGIN ANALYSIS ======
        $marginAnalysis = $this->getMarginAnalysis($period, $year, $month);

        // Helper data
        $categories = Expense::categories();
        $years = range(now()->year - 5, now()->year);
        $months = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];

        return view('admin.finance.index', compact(
            'totalAllTimeRevenue',
            'totalAllTimeExpenses',
            'totalAllTimeProfit',
            'periodData',
            'revenueByProduct',
            'expensesByCategory',
            'chartData',
            'hpp',
            'hppByProduct',
            'marginAnalysis',
            'categories',
            'years',
            'months',
            'period',
            'year',
            'month'
        ));
    }

    private function getPeriodData($period, $year, $month)
    {
        $revenueQuery = Order::query();
        $expenseQuery = Expense::query();
        $ordersCountQuery = Order::query();

        if ($period === 'daily') {
            $revenueQuery->whereDate('created_at', today());
            $expenseQuery->whereDate('expense_date', today());
            $ordersCountQuery->whereDate('created_at', today());
            $label = 'Hari Ini (' . now()->translatedFormat('d M Y') . ')';
        } elseif ($period === 'monthly') {
            $revenueQuery->whereMonth('created_at', $month)->whereYear('created_at', $year);
            $expenseQuery->whereMonth('expense_date', $month)->whereYear('expense_date', $year);
            $ordersCountQuery->whereMonth('created_at', $month)->whereYear('created_at', $year);
            $label = Carbon::create($year, $month)->translatedFormat('F Y');
        } else {
            $revenueQuery->whereYear('created_at', $year);
            $expenseQuery->whereYear('expense_date', $year);
            $ordersCountQuery->whereYear('created_at', $year);
            $label = 'Tahun ' . $year;
        }

        return [
            'label' => $label,
            'revenue' => $revenueQuery->sum('total_price'),
            'expenses' => $expenseQuery->sum('amount'),
            'profit' => $revenueQuery->sum('total_price') - $expenseQuery->sum('amount'),
            'orders_count' => $ordersCountQuery->count(),
            'avg_order_value' => $ordersCountQuery->count() > 0
                ? $revenueQuery->sum('total_price') / $ordersCountQuery->count()
                : 0,
        ];
    }

    private function getChartData($period, $year, $month)
    {
        $data = [];

        if ($period === 'daily') {
            // Last 24 hours
            for ($i = 23; $i >= 0; $i--) {
                $hour = now()->subHours($i);
                $data[] = [
                    'label' => $hour->format('H:00'),
                    'revenue' => Order::whereDate('created_at', today())
                        ->whereTime('created_at', '>=', $hour->format('H:00:00'))
                        ->whereTime('created_at', '<', $hour->addHour()->format('H:00:00'))
                        ->sum('total_price'),
                    'expenses' => Expense::whereDate('expense_date', today())->sum('amount') / 24, // Average per hour
                ];
            }
        } elseif ($period === 'monthly') {
            // Days in month
            $daysInMonth = Carbon::create($year, $month)->daysInMonth;
            for ($day = 1; $day <= $daysInMonth; $day++) {
                $date = Carbon::create($year, $month, $day);
                $data[] = [
                    'label' => $day,
                    'revenue' => Order::whereDate('created_at', $date)->sum('total_price'),
                    'expenses' => Expense::whereDate('expense_date', $date)->sum('amount'),
                ];
            }
        } else {
            // Months in year
            for ($m = 1; $m <= 12; $m++) {
                $data[] = [
                    'label' => Carbon::create($year, $m)->translatedFormat('M'),
                    'revenue' => Order::whereMonth('created_at', $m)->whereYear('created_at', $year)->sum('total_price'),
                    'expenses' => Expense::whereMonth('expense_date', $m)->whereYear('expense_date', $year)->sum('amount'),
                ];
            }
        }

        return $data;
    }

    private function calculateHPP($period, $year, $month)
    {
        // HPP = Total bahan baku yang digunakan untuk produksi
        // Berdasarkan order yang terjual * resep masing-masing produk

        $ordersQuery = Order::with('items.product.ingredients');

        if ($period === 'daily') {
            $ordersQuery->whereDate('created_at', today());
        } elseif ($period === 'monthly') {
            $ordersQuery->whereMonth('created_at', $month)->whereYear('created_at', $year);
        } else {
            $ordersQuery->whereYear('created_at', $year);
        }

        $orders = $ordersQuery->get();
        $totalHPP = 0;
        $hppByProduct = [];

        foreach ($orders as $order) {
            foreach ($order->items as $item) {
                if ($item->product) {
                    $productId = $item->product->id;
                    $productName = $item->product->name;
                    $productPrice = $item->price; // Harga jual saat transaksi

                    // Hitung HPP untuk 1 unit produk ini
                    $hppPerUnit = 0;
                    $ingredientDetails = [];

                    foreach ($item->product->ingredients as $ingredient) {
                        $amountNeeded = $ingredient->pivot->amount_needed;
                        $costPerUnit = $ingredient->cost_per_unit ?? 0;
                        $ingredientCost = $amountNeeded * $costPerUnit;
                        $hppPerUnit += $ingredientCost;

                        $ingredientDetails[] = [
                            'name' => $ingredient->name,
                            'amount' => $amountNeeded,
                            'unit' => $ingredient->unit,
                            'cost_per_unit' => $costPerUnit,
                            'total_cost' => $ingredientCost,
                        ];
                    }

                    $hppForThisItem = $hppPerUnit * $item->qty;
                    $totalHPP += $hppForThisItem;

                    // Aggregate per product
                    if (!isset($hppByProduct[$productId])) {
                        $hppByProduct[$productId] = [
                            'name' => $productName,
                            'price' => $productPrice,
                            'hpp_per_unit' => $hppPerUnit,
                            'profit_per_unit' => $productPrice - $hppPerUnit,
                            'margin_percent' => $productPrice > 0 ? (($productPrice - $hppPerUnit) / $productPrice) * 100 : 0,
                            'qty_sold' => 0,
                            'total_revenue' => 0,
                            'total_hpp' => 0,
                            'total_profit' => 0,
                            'ingredients' => $ingredientDetails,
                        ];
                    }

                    $hppByProduct[$productId]['qty_sold'] += $item->qty;
                    $hppByProduct[$productId]['total_revenue'] += $item->qty * $productPrice;
                    $hppByProduct[$productId]['total_hpp'] += $hppForThisItem;
                    $hppByProduct[$productId]['total_profit'] += ($productPrice - $hppPerUnit) * $item->qty;
                }
            }
        }

        // Sort by total profit descending
        usort($hppByProduct, function ($a, $b) {
            return $b['total_profit'] <=> $a['total_profit'];
        });

        return [
            'total' => $totalHPP,
            'by_product' => $hppByProduct,
        ];
    }

    private function getMarginAnalysis($period, $year, $month)
    {
        $revenue = $this->getPeriodData($period, $year, $month)['revenue'];
        $hppData = $this->calculateHPP($period, $year, $month);
        $hpp = $hppData['total'];
        $expenses = $this->getPeriodData($period, $year, $month)['expenses'];

        $grossProfit = $revenue - $hpp;
        $netProfit = $revenue - $hpp - $expenses;

        return [
            'revenue' => $revenue,
            'hpp' => $hpp,
            'gross_profit' => $grossProfit,
            'gross_margin' => $revenue > 0 ? ($grossProfit / $revenue) * 100 : 0,
            'operating_expenses' => $expenses,
            'net_profit' => $netProfit,
            'net_margin' => $revenue > 0 ? ($netProfit / $revenue) * 100 : 0,
        ];
    }

    public function export(Request $request)
    {
        // TODO: Implement PDF/Excel export
        return back()->with('info', 'Fitur export dalam pengembangan');
    }
}
