<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        Category::insert([
            [
                'name' => 'Elektronik',
                'description' => 'Produk elektronik',
            ],
            [
                'name' => 'Pakaian',
                'description' => 'Produk fashion',
            ],
            [
                'name' => 'Makanan',
                'description' => 'Produk makanan',
            ],
        ]);
    }
}
