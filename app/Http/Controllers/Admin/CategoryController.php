<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Tampilkan daftar kategori
     */
    public function index()
    {
        $categories = Category::withCount('products')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Form tambah kategori
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Simpan kategori baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'icon' => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        Category::create([
            'name' => $request->name,
            'icon' => $request->icon,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil ditambahkan! 🎉');
    }

    /**
     * Form edit kategori
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update kategori
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'icon' => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $category->update([
            'name' => $request->name,
            'icon' => $request->icon,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil diupdate! ✨');
    }

    /**
     * Hapus kategori
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil dihapus! 🗑️');
    }
}
