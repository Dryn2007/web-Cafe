<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $guarded = [];

    protected $casts = [
        'expense_date' => 'date',
        'amount' => 'decimal:2',
        'quantity' => 'decimal:2',
    ];

    // Kategori Pengeluaran
    const CATEGORY_INGREDIENT = 'ingredient_purchase';
    const CATEGORY_OPERATIONAL = 'operational';
    const CATEGORY_SALARY = 'salary';
    const CATEGORY_UTILITY = 'utility';
    const CATEGORY_MAINTENANCE = 'maintenance';
    const CATEGORY_OTHER = 'other';

    public static function categories()
    {
        return [
            self::CATEGORY_INGREDIENT => '🧂 Pembelian Bahan Baku',
            self::CATEGORY_OPERATIONAL => '⚙️ Operasional',
            self::CATEGORY_SALARY => '💼 Gaji Karyawan',
            self::CATEGORY_UTILITY => '💡 Listrik/Air/Internet',
            self::CATEGORY_MAINTENANCE => '🔧 Perawatan/Perbaikan',
            self::CATEGORY_OTHER => '📦 Lainnya',
        ];
    }

    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getCategoryLabelAttribute()
    {
        return self::categories()[$this->category] ?? $this->category;
    }
}
