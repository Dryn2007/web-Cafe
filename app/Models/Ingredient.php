<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    protected $guarded = [];

    // Relasi: Bahan baku digunakan di banyak Produk
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_ingredient')
            ->withPivot('amount_needed');
    }
}
