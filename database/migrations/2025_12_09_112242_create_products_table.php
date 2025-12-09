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
        // A. Tabel Menu (Yang tampil di Kasir)
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Contoh: "Kopi Susu Aren"
            $table->integer('price'); // Contoh: 18000
            $table->string('image')->nullable(); // Foto menu
            $table->boolean('is_available')->default(true);
            $table->timestamps();
        });

        // B. Tabel Resep (Hubungan Menu ke Bahan)
        Schema::create('product_ingredient', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('ingredient_id')->constrained()->onDelete('cascade');
            $table->integer('amount_needed'); // Berapa banyak bahan dipakai per 1 porsi
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_ingredient');
        Schema::dropIfExists('products');
    }
};
