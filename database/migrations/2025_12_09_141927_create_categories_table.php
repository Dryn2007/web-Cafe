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
        // Buat tabel categories
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');        // Nama kategori: "Kopi", "Non-Kopi", "Snack"
            $table->string('icon')->nullable(); // Emoji/icon untuk tampilan
            $table->integer('sort_order')->default(0); // Urutan tampil
            $table->timestamps();
        });

        // Tambahkan kolom category_id ke tabel products
        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->after('id')->constrained()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
        });
        Schema::dropIfExists('categories');
    }
};
