<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Ingredient;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdminProductController extends Controller
{
    // Tampilkan Daftar Produk
    public function index()
    {
        $products = Product::with('ingredients')->latest()->get();
        return view('admin.products.index', compact('products'));
    }

    // Tampilkan Form Tambah Produk
    public function create()
    {
        // Ambil semua bahan baku untuk pilihan resep
        $ingredients = Ingredient::orderBy('name')->get();
        return view('admin.products.create', compact('ingredients'));
    }

    // Simpan Produk & Resep
    public function store(Request $request)
    {
        // 1. Validasi
        $request->validate([
            'name'  => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048', // Max 2MB

            // Validasi Array Resep (Wajib ada minimal 1 bahan)
            'ingredients'          => 'required|array|min:1',
            'ingredients.*.id'     => 'required|exists:ingredients,id',
            'ingredients.*.amount' => 'required|numeric|min:1',
        ]);

        try {
            DB::transaction(function () use ($request) {

                // 2. Upload Gambar (Jika ada)
                $imagePath = null;
                if ($request->hasFile('image')) {
                    $imagePath = $request->file('image')->store('products', 'public');
                }

                // 3. Simpan Data Produk
                $product = Product::create([
                    'name'         => $request->name,
                    'price'        => $request->price,
                    'image'        => $imagePath,
                    'is_available' => true,
                ]);

                // 4. Simpan Resep ke Pivot Table (product_ingredient)
                // Kita loop data dari form dynamic
                foreach ($request->ingredients as $item) {
                    $product->ingredients()->attach($item['id'], [
                        'amount_needed' => $item['amount']
                    ]);
                }
            });

            return redirect()->route('admin.products.index')->with('success', 'Menu berhasil diracik!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

    // (Opsional) Method Destroy/Delete bisa ditambahkan nanti
}
