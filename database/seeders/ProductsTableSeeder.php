<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categoryIds = Category::pluck('id', 'category_name');

        $products = [
            [
                'product_name' => 'Ноутбук ASUS ROG Strix G15',
                'category_id' => $categoryIds['Ноутбуки'],
                'unit_price' => 89990.00,
                'stock_quantity' => 10,
                'description' => 'Игровой ноутбук с процессором AMD Ryzen 7, 16 ГБ RAM, SSD 512 ГБ, видеокарта RTX 3050 Ti.',
                'image_url' => 'images/products/notebook.png',
            ],
            [
                'product_name' => 'Смартфон Samsung Galaxy S23',
                'category_id' => $categoryIds['Смартфоны'],
                'unit_price' => 64990.00,
                'stock_quantity' => 15,
                'description' => 'Флагманский смартфон с камерой 50 МП, экран 6.1 дюйма, 8 ГБ RAM, 256 ГБ памяти.',
                'image_url' => 'images/products/yandex.png',
            ],
            [
                'product_name' => 'Планшет Apple iPad Pro 11',
                'category_id' => $categoryIds['Планшеты'],
                'unit_price' => 79990.00,
                'stock_quantity' => 5,
                'description' => 'Профессиональный планшет с чипом M2, 128 ГБ, поддержка Apple Pencil.',
                'image_url' => 'images/products/mouse.png',
            ],
            [
                'product_name' => 'Видеокарта MSI GeForce RTX 4060',
                'category_id' => $categoryIds['Комплектующие'],
                'unit_price' => 35990.00,
                'stock_quantity' => 8,
                'description' => 'Игровая видеокарта с 8 ГБ GDDR6, поддержка трассировки лучей.',
                'image_url' => 'images/products/videocart.png',
            ],
            [
                'product_name' => 'Монитор LG UltraGear 27"',
                'category_id' => $categoryIds['Мониторы'],
                'unit_price' => 27990.00,
                'stock_quantity' => 12,
                'description' => 'Игровой монитор с частотой обновления 144 Гц, матрица IPS, разрешение QHD.',
                'image_url' => 'images/products/pc.png',
            ],
            [
                'product_name' => 'Принтер HP LaserJet M141w',
                'category_id' => $categoryIds['Принтеры'],
                'unit_price' => 8990.00,
                'stock_quantity' => 20,
                'description' => 'Компактный лазерный принтер с Wi-Fi, скорость печати 20 стр/мин.',
                'image_url' => 'images/products/music.png',
            ],
            [
                'product_name' => 'Клавиатура Logitech MX Mechanical',
                'category_id' => $categoryIds['Аксессуары'],
                'unit_price' => 12990.00,
                'stock_quantity' => 7,
                'description' => 'Механическая клавиатура с низкопрофильными переключателями, подсветка.',
                'image_url' => 'images/products/keyboard.png',
            ],
            [
                'product_name' => 'Мышь Razer DeathAdder V2',
                'category_id' => $categoryIds['Аксессуары'],
                'unit_price' => 4990.00,
                'stock_quantity' => 25,
                'description' => 'Игровая мышь с сенсором 20000 DPI, оптические переключатели.',
                'image_url' => 'images/products/mouse.png',
            ],
            [
                'product_name' => 'Внешний жесткий диск Transcend 1TB',
                'category_id' => $categoryIds['Аксессуары'],
                'unit_price' => 4990.00,
                'stock_quantity' => 30,
                'description' => 'Портативный HDD USB 3.0, ударопрочный корпус.',
                'image_url' => 'images/products/monitor.png',
            ],
            [
                'product_name' => 'Ноутбук Apple MacBook Air M2',
                'category_id' => $categoryIds['Ноутбуки'],
                'unit_price' => 119990.00,
                'stock_quantity' => 4,
                'description' => 'Лёгкий ноутбук с чипом M2, 8 ГБ RAM, 256 ГБ SSD, Retina экран.',
                'image_url' => 'images/products/hearphone.png',
            ],
             [
                'product_name' => 'Ноутбук ASUS ROG Strix G15',
                'category_id' => $categoryIds['Ноутбуки'],
                'unit_price' => 89990.00,
                'stock_quantity' => 10,
                'description' => 'Игровой ноутбук с процессором AMD Ryzen 7, 16 ГБ RAM, SSD 512 ГБ, видеокарта RTX 3050 Ti.',
                'image_url' => 'images/products/notebook.png',
            ],
            [
                'product_name' => 'Смартфон Samsung Galaxy S23',
                'category_id' => $categoryIds['Смартфоны'],
                'unit_price' => 64990.00,
                'stock_quantity' => 15,
                'description' => 'Флагманский смартфон с камерой 50 МП, экран 6.1 дюйма, 8 ГБ RAM, 256 ГБ памяти.',
                'image_url' => 'images/products/yandex.png',
            ],
            [
                'product_name' => 'Планшет Apple iPad Pro 11',
                'category_id' => $categoryIds['Планшеты'],
                'unit_price' => 79990.00,
                'stock_quantity' => 5,
                'description' => 'Профессиональный планшет с чипом M2, 128 ГБ, поддержка Apple Pencil.',
                'image_url' => 'images/products/mouse.png',
            ],
            [
                'product_name' => 'Видеокарта MSI GeForce RTX 4060',
                'category_id' => $categoryIds['Комплектующие'],
                'unit_price' => 35990.00,
                'stock_quantity' => 8,
                'description' => 'Игровая видеокарта с 8 ГБ GDDR6, поддержка трассировки лучей.',
                'image_url' => 'images/products/videocart.png',
            ],
            [
                'product_name' => 'Монитор LG UltraGear 27"',
                'category_id' => $categoryIds['Мониторы'],
                'unit_price' => 27990.00,
                'stock_quantity' => 12,
                'description' => 'Игровой монитор с частотой обновления 144 Гц, матрица IPS, разрешение QHD.',
                'image_url' => 'images/products/pc.png',
            ],
            [
                'product_name' => 'Принтер HP LaserJet M141w',
                'category_id' => $categoryIds['Принтеры'],
                'unit_price' => 8990.00,
                'stock_quantity' => 20,
                'description' => 'Компактный лазерный принтер с Wi-Fi, скорость печати 20 стр/мин.',
                'image_url' => 'images/products/music.png',
            ],
            [
                'product_name' => 'Клавиатура Logitech MX Mechanical',
                'category_id' => $categoryIds['Аксессуары'],
                'unit_price' => 12990.00,
                'stock_quantity' => 7,
                'description' => 'Механическая клавиатура с низкопрофильными переключателями, подсветка.',
                'image_url' => 'images/products/keyboard.png',
            ],
            [
                'product_name' => 'Мышь Razer DeathAdder V2',
                'category_id' => $categoryIds['Аксессуары'],
                'unit_price' => 4990.00,
                'stock_quantity' => 25,
                'description' => 'Игровая мышь с сенсором 20000 DPI, оптические переключатели.',
                'image_url' => 'images/products/mouse.png',
            ],
            [
                'product_name' => 'Внешний жесткий диск Transcend 1TB',
                'category_id' => $categoryIds['Аксессуары'],
                'unit_price' => 4990.00,
                'stock_quantity' => 30,
                'description' => 'Портативный HDD USB 3.0, ударопрочный корпус.',
                'image_url' => 'images/products/monitor.png',
            ],
            [
                'product_name' => 'Ноутбук Apple MacBook Air M2',
                'category_id' => $categoryIds['Ноутбуки'],
                'unit_price' => 119990.00,
                'stock_quantity' => 4,
                'description' => 'Лёгкий ноутбук с чипом M2, 8 ГБ RAM, 256 ГБ SSD, Retina экран.',
                'image_url' => 'images/products/hearphone.png',
            ],
             [
                'product_name' => 'Ноутбук ASUS ROG Strix G15',
                'category_id' => $categoryIds['Ноутбуки'],
                'unit_price' => 89990.00,
                'stock_quantity' => 10,
                'description' => 'Игровой ноутбук с процессором AMD Ryzen 7, 16 ГБ RAM, SSD 512 ГБ, видеокарта RTX 3050 Ti.',
                'image_url' => 'images/products/notebook.png',
            ],
            [
                'product_name' => 'Смартфон Samsung Galaxy S23',
                'category_id' => $categoryIds['Смартфоны'],
                'unit_price' => 64990.00,
                'stock_quantity' => 15,
                'description' => 'Флагманский смартфон с камерой 50 МП, экран 6.1 дюйма, 8 ГБ RAM, 256 ГБ памяти.',
                'image_url' => 'images/products/yandex.png',
            ],
            [
                'product_name' => 'Планшет Apple iPad Pro 11',
                'category_id' => $categoryIds['Планшеты'],
                'unit_price' => 79990.00,
                'stock_quantity' => 5,
                'description' => 'Профессиональный планшет с чипом M2, 128 ГБ, поддержка Apple Pencil.',
                'image_url' => 'images/products/mouse.png',
            ],
            [
                'product_name' => 'Видеокарта MSI GeForce RTX 4060',
                'category_id' => $categoryIds['Комплектующие'],
                'unit_price' => 35990.00,
                'stock_quantity' => 8,
                'description' => 'Игровая видеокарта с 8 ГБ GDDR6, поддержка трассировки лучей.',
                'image_url' => 'images/products/videocart.png',
            ],
            [
                'product_name' => 'Монитор LG UltraGear 27"',
                'category_id' => $categoryIds['Мониторы'],
                'unit_price' => 27990.00,
                'stock_quantity' => 12,
                'description' => 'Игровой монитор с частотой обновления 144 Гц, матрица IPS, разрешение QHD.',
                'image_url' => 'images/products/pc.png',
            ],
            [
                'product_name' => 'Принтер HP LaserJet M141w',
                'category_id' => $categoryIds['Принтеры'],
                'unit_price' => 8990.00,
                'stock_quantity' => 20,
                'description' => 'Компактный лазерный принтер с Wi-Fi, скорость печати 20 стр/мин.',
                'image_url' => 'images/products/music.png',
            ],
            [
                'product_name' => 'Клавиатура Logitech MX Mechanical',
                'category_id' => $categoryIds['Аксессуары'],
                'unit_price' => 12990.00,
                'stock_quantity' => 7,
                'description' => 'Механическая клавиатура с низкопрофильными переключателями, подсветка.',
                'image_url' => 'images/products/keyboard.png',
            ],
            [
                'product_name' => 'Мышь Razer DeathAdder V2',
                'category_id' => $categoryIds['Аксессуары'],
                'unit_price' => 4990.00,
                'stock_quantity' => 25,
                'description' => 'Игровая мышь с сенсором 20000 DPI, оптические переключатели.',
                'image_url' => 'images/products/mouse.png',
            ],
            [
                'product_name' => 'Внешний жесткий диск Transcend 1TB',
                'category_id' => $categoryIds['Аксессуары'],
                'unit_price' => 4990.00,
                'stock_quantity' => 30,
                'description' => 'Портативный HDD USB 3.0, ударопрочный корпус.',
                'image_url' => 'images/products/monitor.png',
            ],
            [
                'product_name' => 'Внешний жесткий диск Transcend 1TB',
                'category_id' => $categoryIds['Аксессуары'],
                'unit_price' => 4990.00,
                'stock_quantity' => 30,
                'description' => 'Портативный HDD USB 3.0, ударопрочный корпус.',
                'image_url' => 'images/products/monitor.png',
            ],
                        [
                'product_name' => 'Внешний жесткий диск Transcend 1TB',
                'category_id' => $categoryIds['Аксессуары'],
                'unit_price' => 4990.00,
                'stock_quantity' => 30,
                'description' => 'Портативный HDD USB 3.0, ударопрочный корпус.',
                'image_url' => NULL,
            ],
            [
                'product_name' => 'Внешний жесткий диск Transcend 1TB',
                'category_id' => $categoryIds['Аксессуары'],
                'unit_price' => 4990.00,
                'stock_quantity' => 30,
                'description' => 'Портативный HDD USB 3.0, ударопрочный корпус.',
                'image_url' => 'images/products/monitor.png',
            ],
            [
                'product_name' => 'Внешний жесткий диск Transcend 1TB',
                'category_id' => $categoryIds['Аксессуары'],
                'unit_price' => 4990.00,
                'stock_quantity' => 30,
                'description' => 'Портативный HDD USB 3.0, ударопрочный корпус.',
                'image_url' => 'images/products/monitor.png',
            ],
            [
                'product_name' => 'Ноутбук Apple MacBook Air M2',
                'category_id' => $categoryIds['Ноутбуки'],
                'unit_price' => 119990.00,
                'stock_quantity' => 4,
                'description' => 'Лёгкий ноутбук с чипом M2, 8 ГБ RAM, 256 ГБ SSD, Retina экран.',
                'image_url' => 'images/products/hearphone.png',
            ],
        ];

        foreach($products as $product){
            Product::create($product);
        }
    }
}
