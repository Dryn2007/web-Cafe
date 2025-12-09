<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Ingredient;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdminProductController extends Controller
{
    // Tampilkan Daftar Produk
    public function index()
    {
        $products = Product::with(['ingredients', 'category'])->latest()->get();
        $categories = Category::orderBy('sort_order')->orderBy('name')->get();
        return view('admin.products.index', compact('products', 'categories'));
    }

    // Tampilkan Form Tambah Produk
    public function create()
    {
        // Ambil semua bahan baku untuk pilihan resep
        $ingredients = Ingredient::orderBy('name')->get();
        // Ambil semua kategori untuk pilihan
        $categories = Category::orderBy('sort_order')->orderBy('name')->get();
        return view('admin.products.create', compact('ingredients', 'categories'));
    }

    // Simpan Produk & Resep
    public function store(Request $request)
    {
        // 1. Validasi
        $request->validate([
            'name'  => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048', // Max 2MB
            'category_id' => 'nullable|exists:categories,id',

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
                    'category_id'  => $request->category_id,
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

    // Tampilkan Form Edit Produk
    public function edit(Product $product)
    {
        $product->load('ingredients');
        $ingredients = Ingredient::orderBy('name')->get();
        $categories = Category::orderBy('sort_order')->orderBy('name')->get();
        return view('admin.products.edit', compact('product', 'ingredients', 'categories'));
    }

    // Update Produk & Resep
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048',
            'category_id' => 'nullable|exists:categories,id',
            'ingredients'          => 'required|array|min:1',
            'ingredients.*.id'     => 'required|exists:ingredients,id',
            'ingredients.*.amount' => 'required|numeric|min:1',
        ]);

        try {
            DB::transaction(function () use ($request, $product) {

                // Upload Gambar Baru (Jika ada)
                if ($request->hasFile('image')) {
                    // Hapus gambar lama jika ada
                    if ($product->image) {
                        Storage::disk('public')->delete($product->image);
                    }
                    $imagePath = $request->file('image')->store('products', 'public');
                    $product->image = $imagePath;
                }

                // Update Data Produk
                $product->update([
                    'name'         => $request->name,
                    'price'        => $request->price,
                    'image'        => $product->image,
                    'category_id'  => $request->category_id,
                ]);

                // Sync Resep (hapus lama, tambah baru)
                $syncData = [];
                foreach ($request->ingredients as $item) {
                    $syncData[$item['id']] = ['amount_needed' => $item['amount']];
                }
                $product->ingredients()->sync($syncData);
            });

            return redirect()->route('admin.products.index')->with('success', 'Menu berhasil diupdate! ✨');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal mengupdate: ' . $e->getMessage());
        }
    }

    // Hapus Produk
    public function destroy(Product $product)
    {
        try {
            // Hapus gambar jika ada
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }

            // Hapus relasi resep dan produk
            $product->ingredients()->detach();
            $product->delete();

            return redirect()->route('admin.products.index')->with('success', 'Menu berhasil dihapus! 🗑️');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }
}
