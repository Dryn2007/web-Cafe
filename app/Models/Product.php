<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = [];

    /**
     * Relasi: Produk milik satu Kategori
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relasi: Produk punya banyak Bahan Baku (lewat tabel resep)
     */
    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class, 'product_ingredient')
            ->withPivot('amount_needed'); // Penting agar jumlah takaran terbaca
    }

    /**
     * Cek apakah stok bahan baku cukup untuk membuat produk ini
     * @param int $qty Jumlah produk yang ingin dibuat (default: 1)
     * @return bool
     */
    public function hasEnoughStock(int $qty = 1): bool
    {
        // Jika produk tidak punya resep/bahan, anggap selalu tersedia
        if ($this->ingredients->isEmpty()) {
            return true;
        }

        foreach ($this->ingredients as $ingredient) {
            $amountNeeded = $ingredient->pivot->amount_needed * $qty;
            if ($ingredient->stock < $amountNeeded) {
                return false;
            }
        }

        return true;
    }

    /**
     * Hitung berapa banyak produk ini bisa dibuat berdasarkan stok
     * @return int
     */
    public function getMaxQuantityAvailable(): int
    {
        if ($this->ingredients->isEmpty()) {
            return 999; // Unlimited jika tidak ada resep
        }

        $maxQty = PHP_INT_MAX;

        foreach ($this->ingredients as $ingredient) {
            if ($ingredient->pivot->amount_needed > 0) {
                $possibleQty = floor($ingredient->stock / $ingredient->pivot->amount_needed);
                $maxQty = min($maxQty, $possibleQty);
            }
        }

        return $maxQty === PHP_INT_MAX ? 999 : (int) $maxQty;
    }

    /**
     * Dapatkan bahan yang stoknya kurang/habis
     * @return array
     */
    public function getOutOfStockIngredients(): array
    {
        $outOfStock = [];

        foreach ($this->ingredients as $ingredient) {
            if ($ingredient->stock < $ingredient->pivot->amount_needed) {
                $outOfStock[] = [
                    'name' => $ingredient->name,
                    'needed' => $ingredient->pivot->amount_needed,
                    'available' => $ingredient->stock,
                    'unit' => $ingredient->unit,
                ];
            }
        }

        return $outOfStock;
    }
}
