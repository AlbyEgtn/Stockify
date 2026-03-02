<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan category & supplier ada
        if (Category::count() == 0 || Supplier::count() == 0) {
            $this->command->info('Category atau Supplier belum ada. Jalankan seeder Category & Supplier dulu.');
            return;
        }

        $categories = Category::all();
        $suppliers  = Supplier::all();

        for ($i = 1; $i <= 20; $i++) {

            $product = Product::create([
                'name'           => 'Produk Dummy ' . $i,
                'sku'            => 'SKU-' . strtoupper(Str::random(6)),
                'category_id'    => $categories->random()->id,
                'supplier_id'    => $suppliers->random()->id,
                'purchase_price' => rand(10000, 50000),
                'selling_price'  => rand(60000, 150000),
                'stock'          => rand(1, 100),
                'minimum_stock'  => rand(5, 20),
                'description'    => 'Deskripsi produk dummy ke-' . $i,
            ]);

            // Tambahkan atribut (relasi dari instance!)
           $product->productAttributes()->createMany([
                [
                    'name'  => 'Warna',
                    'value' => collect(['Merah','Biru','Hitam','Putih'])->random()
                ],
                [
                    'name'  => 'Ukuran',
                    'value' => collect(['S','M','L','XL'])->random()
                ],
            ]);

        }
    }
}
