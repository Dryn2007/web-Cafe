<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // A. Tabel Order Utama
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            // Relasi ke User yang Login
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('customer_name'); // Kita simpan juga namanya sebagai snapshot
            $table->string('queue_number'); // A-001
            $table->integer('total_price');
            // Tambah kolom metode pembayaran
            $table->string('payment_method'); // cash, qris, transfer
            $table->enum('status', ['pending', 'paid', 'completed', 'cancelled'])->default('pending');
            $table->timestamps();
        });

        // B. Tabel Detail Item (Apa saja yang dipesan)
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained(); // Jangan dihapus kalau produk dihapus
            $table->integer('qty');
            $table->integer('price'); // Harga saat beli
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};
