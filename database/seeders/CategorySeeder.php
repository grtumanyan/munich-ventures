<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['title' => 'Category 1', 'description' => 'Category 1 description'],
            ['title' => 'Category 2', 'description' => 'Category 2 description'],
            ['title' => 'Category 3', 'description' => 'Category 3 description'],
            ['title' => 'Category 4', 'description' => 'Category 4 description'],
            ['title' => 'Category 5', 'description' => 'Category 5 description'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
