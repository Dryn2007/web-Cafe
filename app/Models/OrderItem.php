<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    // Agar bisa diisi massal (create)
    protected $guarded = [];

    // Relasi ke Produk (agar kita bisa tahu ini item apa)
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Relasi ke Order Utama (kebalikan)
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
