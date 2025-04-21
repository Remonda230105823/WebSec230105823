<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'code' => 'LAPTOP001',
                'name' => 'Laptop Pro',
                'model' => 'LP-2025-ULTRA',
                'description' => 'High-performance laptop with the latest specs',
                'price' => 999.99,
                'quantity' => 10,
                'photo' => 'laptop.jpg'
            ],
            [
                'code' => 'PHONE001',
                'name' => 'SmartPhone X',
                'model' => 'SPX-2025',
                'description' => 'Latest smartphone with stunning camera',
                'price' => 599.99,
                'quantity' => 20,
                'photo' => 'phone.jpg'
            ],
            [
                'code' => 'TABLET001',
                'name' => 'Tablet Pro',
                'model' => 'TP-2025',
                'description' => 'Ultra-thin tablet with high-resolution display',
                'price' => 349.99,
                'quantity' => 15,
                'photo' => 'tablet.jpg'
            ],
            [
                'code' => 'HEADPHONE001',
                'name' => 'Noise Cancelling Headphones',
                'model' => 'NCH-2025',
                'description' => 'Premium headphones with active noise cancellation',
                'price' => 149.99,
                'quantity' => 30,
                'photo' => 'headphones.jpg'
            ],
            [
                'code' => 'SPEAKER001',
                'name' => 'Wireless Speaker',
                'model' => 'WS-2025',
                'description' => 'Portable wireless speaker with great sound quality',
                'price' => 79.99,
                'quantity' => 25,
                'photo' => 'speaker.jpg'
            ]
        ];
        
        foreach($products as $product) {
            Product::create($product);
        }
    }
}
