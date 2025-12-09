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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string('category'); // ingredient_purchase, operational, salary, etc
            $table->string('description');
            $table->decimal('amount', 15, 2);
            $table->date('expense_date');
            $table->foreignId('ingredient_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('quantity', 10, 2)->nullable(); // Jumlah bahan yang dibeli
            $table->string('unit')->nullable(); // Satuan
            $table->string('supplier')->nullable(); // Nama supplier
            $table->text('notes')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // Admin yang input
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
