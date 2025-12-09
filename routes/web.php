<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\IngredientController;
use App\Http\Controllers\AdminProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ExpenseController;
use App\Http\Controllers\Admin\FinanceController;

// ==========================================
// 1. ZONA PUBLIK (Bisa diakses tanpa login)
// ==========================================

// Halaman Menu (Katalog)
Route::get('/', [OrderController::class, 'index'])->name('order.index');


// ==========================================
// 2. ZONA MEMBER / USER (Harus Login & Role = user)
// ==========================================
Route::middleware(['auth', 'role:user'])->group(function () {
    // Halaman Checkout
    Route::get('/checkout', [OrderController::class, 'checkout'])->name('order.checkout');

    // Proses Order
    Route::post('/order', [OrderController::class, 'store'])->name('order.store');

    // Halaman Struk
    Route::get('/struk/{id}', [OrderController::class, 'show'])->name('order.show');

    // Riwayat Pesanan
    Route::get('/riwayat', [OrderController::class, 'history'])->name('order.history');
});


// ==========================================
// 3. ZONA ADMIN (Harus Login & Role = admin)
// ==========================================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard Admin: http://127.0.0.1:8000/admin/dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Nanti disini tempat route Manage Stok, Manage Menu, Laporan, dll.
    Route::get('/stok', function () {
        return "Halaman Stok";
    })->name('stok');

    Route::resource('ingredients', IngredientController::class);

    // Produk
    Route::get('/products', [AdminProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [AdminProductController::class, 'create'])->name('products.create');
    Route::post('/products', [AdminProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [AdminProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [AdminProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [AdminProductController::class, 'destroy'])->name('products.destroy');

    // Kategori
    Route::resource('categories', CategoryController::class);

    // Pengeluaran
    Route::resource('expenses', ExpenseController::class);

    // Laporan Keuangan
    Route::get('/finance', [FinanceController::class, 'index'])->name('finance.index');
    Route::get('/finance/export', [FinanceController::class, 'export'])->name('finance.export');
});


// Route bawaan Breeze (Login/Register/Logout)
require __DIR__ . '/auth.php';
