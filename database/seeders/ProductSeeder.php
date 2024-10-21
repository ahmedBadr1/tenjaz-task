<?php

namespace Database\Seeders;

use App\Enums\UserTypes;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::factory(5)->create()->each(function ($product) {
            $product->prices()->createMany([
                ['type' => UserTypes::Normal->value, 'price' => 100],
                ['type' => UserTypes::Silver->value, 'price' => 90],
                ['type' => UserTypes::Gold->value, 'price' => 80],
            ]);
        });
    }
}
