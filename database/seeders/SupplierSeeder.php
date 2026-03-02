<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        Supplier::insert([
            [
                'name' => 'PT Sumber Makmur',
                'address' => 'Jakarta',
                'phone' => '08123456789',
                'email' => 'supplier1@mail.com',
            ],
            [
                'name' => 'CV Jaya Abadi',
                'address' => 'Bandung',
                'phone' => '08234567890',
                'email' => 'supplier2@mail.com',
            ],
        ]);
    }
}
