<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class UpdateProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all existing product IDs
        $existingProductIds = Product::pluck('id')->toArray();
        
        // Create product image directories if they don't exist
        if (!File::exists(public_path('images'))) {
            File::makeDirectory(public_path('images'));
        }

        // Download images from URLs and save them locally
        $lgTvImageName = 'lg_tv_50.jpg';
        $toshibaRefrigeratorImageName = 'toshiba_refrigerator_14.jpg';

        // Save image URLs to local files
        file_put_contents(
            public_path('images/' . $lgTvImageName),
            file_get_contents('https://images.unsplash.com/photo-1593784991095-a205069470b6?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.0.3')
        );

        file_put_contents(
            public_path('images/' . $toshibaRefrigeratorImageName),
            file_get_contents('https://images.unsplash.com/photo-1584568694244-14fbdf83bd30?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.0.3')
        );

        // Delete all products without purchase references
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Delete all products not referenced in purchases table
        $productsWithPurchases = DB::table('purchases')->pluck('product_id')->unique()->toArray();
        Product::whereNotIn('id', $productsWithPurchases)->delete();
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Add LG TV 50
        Product::create([
            'code' => 'LG-TV-50',
            'name' => 'LG TV 50"',
            'model' => 'UHD Smart TV 50UN7300',
            'description' => '50-inch Smart LED TV with 4K Ultra HD resolution, HDR support, and built-in streaming apps.',
            'price' => 499.99,
            'quantity' => 10,
            'photo' => $lgTvImageName
        ]);

        // Add Toshiba Refrigerator 14
        Product::create([
            'code' => 'TOSH-REF-14',
            'name' => 'Toshiba Refrigerator 14',
            'model' => 'GR-RF646WE-PGJ',
            'description' => '14 cubic ft French door refrigerator with adjustable shelves, LED lighting, and energy-efficient operation.',
            'price' => 899.99,
            'quantity' => 5,
            'photo' => $toshibaRefrigeratorImageName
        ]);
    }
}
