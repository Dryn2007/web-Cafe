<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $guarded = [];

    /**
     * Relasi: Kategori punya banyak Produk
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
