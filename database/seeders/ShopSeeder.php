<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ingredient;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ShopSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Reset Database (Hapus data lama biar bersih)
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Ingredient::truncate();
        Product::truncate();
        DB::table('product_ingredient')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // ==========================================
        // TAHAP 1: INPUT GUDANG BAHAN BAKU (INGREDIENTS)
        // ==========================================
        // Harga (cost_per_unit) adalah estimasi harga beli

        $bijiKopi  = Ingredient::create(['name' => 'Biji Kopi Arabica', 'stock' => 5000, 'unit' => 'gr', 'cost_per_unit' => 300]); // Rp 300/gr
        $susu      = Ingredient::create(['name' => 'Susu Fresh Milk', 'stock' => 10000, 'unit' => 'ml', 'cost_per_unit' => 25]);   // Rp 25/ml
        $gulaAren  = Ingredient::create(['name' => 'Gula Aren Cair', 'stock' => 5000, 'unit' => 'ml', 'cost_per_unit' => 40]);    // Rp 40/ml
        $gulaPasir = Ingredient::create(['name' => 'Gula Pasir', 'stock' => 5000, 'unit' => 'gr', 'cost_per_unit' => 15]);       // Rp 15/gr
        $coklat    = Ingredient::create(['name' => 'Bubuk Coklat Premium', 'stock' => 2000, 'unit' => 'gr', 'cost_per_unit' => 150]); // Rp 150/gr
        $matcha    = Ingredient::create(['name' => 'Bubuk Matcha', 'stock' => 1000, 'unit' => 'gr', 'cost_per_unit' => 400]);     // Rp 400/gr
        $teh       = Ingredient::create(['name' => 'Teh Hitam (Bag)', 'stock' => 500, 'unit' => 'pcs', 'cost_per_unit' => 1000]);   // Rp 1000/kantong
        $lemon     = Ingredient::create(['name' => 'Buah Lemon', 'stock' => 100, 'unit' => 'pcs', 'cost_per_unit' => 2000]);      // Rp 2000/potong
        $kentang   = Ingredient::create(['name' => 'Kentang Beku', 'stock' => 5000, 'unit' => 'gr', 'cost_per_unit' => 60]);       // Rp 60/gr
        $roti      = Ingredient::create(['name' => 'Roti Tawar Tebal', 'stock' => 200, 'unit' => 'pcs', 'cost_per_unit' => 1500]);   // Rp 1500/lembar
        $selai     = Ingredient::create(['name' => 'Selai Coklat', 'stock' => 3000, 'unit' => 'gr', 'cost_per_unit' => 100]);      // Rp 100/gr

        // Packaging
        $cup       = Ingredient::create(['name' => 'Cup Plastik + Straw', 'stock' => 1000, 'unit' => 'pcs', 'cost_per_unit' => 800]);
        $paperBowl = Ingredient::create(['name' => 'Paper Bowl (Makanan)', 'stock' => 1000, 'unit' => 'pcs', 'cost_per_unit' => 1000]);


        // ==========================================
        // TAHAP 2: INPUT PRODUK & RESEP
        // ==========================================

        // 1. ESPRESSO (Kopi murni)
        $p1 = Product::create(['name' => 'Espresso Single', 'price' => 15000, 'image' => null, 'is_available' => true]);
        $p1->ingredients()->attach([
            $bijiKopi->id => ['amount_needed' => 18], // 18gr kopi
            $cup->id      => ['amount_needed' => 1],
        ]);

        // 2. AMERICANO (Kopi + Air)
        $p2 = Product::create(['name' => 'Iced Americano', 'price' => 18000, 'image' => null, 'is_available' => true]);
        $p2->ingredients()->attach([
            $bijiKopi->id => ['amount_needed' => 18],
            $gulaPasir->id => ['amount_needed' => 10], // Sedikit gula (opsional)
            $cup->id      => ['amount_needed' => 1],
        ]);

        // 3. KOPI SUSU GULA AREN (Best Seller)
        $p3 = Product::create(['name' => 'Kopi Susu Gula Aren', 'price' => 25000, 'image' => null, 'is_available' => true]);
        $p3->ingredients()->attach([
            $bijiKopi->id => ['amount_needed' => 18],
            $susu->id     => ['amount_needed' => 150], // 150ml susu
            $gulaAren->id => ['amount_needed' => 30],  // 30ml gula aren
            $cup->id      => ['amount_needed' => 1],
        ]);

        // 4. CAFFE LATTE
        $p4 = Product::create(['name' => 'Caffe Latte', 'price' => 28000, 'image' => null, 'is_available' => true]);
        $p4->ingredients()->attach([
            $bijiKopi->id => ['amount_needed' => 18],
            $susu->id     => ['amount_needed' => 200], // Lebih banyak susu dari Kopsus
            $gulaPasir->id => ['amount_needed' => 10],
            $cup->id      => ['amount_needed' => 1],
        ]);

        // 5. SIGNATURE CHOCOLATE (Non-Kopi)
        $p5 = Product::create(['name' => 'Signature Dark Chocolate', 'price' => 28000, 'image' => null, 'is_available' => true]);
        $p5->ingredients()->attach([
            $coklat->id   => ['amount_needed' => 30],  // 30gr bubuk coklat
            $susu->id     => ['amount_needed' => 150],
            $gulaPasir->id => ['amount_needed' => 15],
            $cup->id      => ['amount_needed' => 1],
        ]);

        // 6. MATCHA LATTE (Non-Kopi)
        $p6 = Product::create(['name' => 'Uji Matcha Latte', 'price' => 30000, 'image' => null, 'is_available' => true]);
        $p6->ingredients()->attach([
            $matcha->id   => ['amount_needed' => 20],  // 20gr matcha mahal
            $susu->id     => ['amount_needed' => 180],
            $gulaPasir->id => ['amount_needed' => 20],
            $cup->id      => ['amount_needed' => 1],
        ]);

        // 7. LEMON TEA (Segar)
        $p7 = Product::create(['name' => 'Iced Lemon Tea', 'price' => 18000, 'image' => null, 'is_available' => true]);
        $p7->ingredients()->attach([
            $teh->id      => ['amount_needed' => 1],   // 1 kantong teh
            $lemon->id    => ['amount_needed' => 1],   // 1 iris lemon
            $gulaPasir->id => ['amount_needed' => 30],  // Butuh manis
            $cup->id      => ['amount_needed' => 1],
        ]);

        // 8. FRENCH FRIES (Makanan)
        $p8 = Product::create(['name' => 'French Fries', 'price' => 20000, 'image' => null, 'is_available' => true]);
        $p8->ingredients()->attach([
            $kentang->id   => ['amount_needed' => 200], // 200gr kentang
            $paperBowl->id => ['amount_needed' => 1],
        ]);

        // 9. ROTI BAKAR COKLAT (Makanan)
        $p9 = Product::create(['name' => 'Roti Bakar Choco Crunchy', 'price' => 22000, 'image' => null, 'is_available' => true]);
        $p9->ingredients()->attach([
            $roti->id      => ['amount_needed' => 2],   // 2 lembar roti
            $selai->id     => ['amount_needed' => 40],  // 40gr selai
            $paperBowl->id => ['amount_needed' => 1],
        ]);

        // 10. CHOCO COFFEE (Campuran)
        $p10 = Product::create(['name' => 'Mocha Latte', 'price' => 32000, 'image' => null, 'is_available' => true]);
        $p10->ingredients()->attach([
            $bijiKopi->id => ['amount_needed' => 18],
            $coklat->id   => ['amount_needed' => 20],
            $susu->id     => ['amount_needed' => 150],
            $cup->id      => ['amount_needed' => 1],
        ]);
    }
}
