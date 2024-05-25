<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'title' => 'Product 1',
                'description' => 'Product 1 description',
                'sku' => 'PROD1234',
                'price' => 100,
                'category_id' => 1
            ],
            [
                'title' => 'Product 2',
                'description' => 'Product 2 description',
                'sku' => 'PROD4321',
                'price' => 200,
                'category_id' => 2
            ]
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
