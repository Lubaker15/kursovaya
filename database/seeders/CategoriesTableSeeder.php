<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['category_name' => 'Ноутбуки'],
            ['category_name' => 'Смартфоны'],
            ['category_name' => 'Планшеты'],
            ['category_name' => 'Комплектующие'],
            ['category_name' => 'Мониторы'],
            ['category_name' => 'Принтеры'],
            ['category_name' => 'Аксессуары'],
        ];

        foreach($categories as $category){
            Category::create($category);
        }
    }
}
