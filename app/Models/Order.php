<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Order extends Model
{
    protected $guarded = [];

    // Relasi: Order punya banyak Item
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Relasi: Order milik User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
