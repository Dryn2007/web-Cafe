<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = [];

    // Relasi: Produk punya banyak Bahan Baku (lewat tabel resep)
    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class, 'product_ingredient')
            ->withPivot('amount_needed'); // Penting agar jumlah takaran terbaca
    }
}
