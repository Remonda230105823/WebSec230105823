<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/multable', function (Request $request) {
    $j= $request->number;
    $msg =$request->msg;
    return view('multable', compact("j", "msg")); //multable.blade.php
});

Route::get('/even', function () {
    return view('even'); //even.blade.php
});

Route::get('/prime', function () {
    return view('prime'); //prime.blade.php
});

Route::get('/test', function () {
    return view('test'); //prime.blade.php
});

Route::get('/minitest', function () {
    $bill = [
        ['item' => 'Milk', 'quantity' => 2, 'price' => 1.50],
        ['item' => 'Bread', 'quantity' => 1, 'price' => 2.00],
        ['item' => 'Eggs', 'quantity' => 12, 'price' => 3.50],
    ];
    
    return view('minitest', compact('bill')); // Passes the $bill variable to the view
});

Route::get('/transcript', function () {
    $transcript = [
        ['course' => 'Mathematics', 'grade' => 'A'],
        ['course' => 'Physics', 'grade' => 'B+'],
        ['course' => 'Computer Science', 'grade' => 'A-'],
        ['course' => 'Networking', 'grade' => 'B'],
    ];
    return view('transcript', ['transcript' => $transcript]);
});

Route::get('/products', function () {
    $products = [
        [
            'name' => 'Laptop',
            'image' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTZqQJRLgY5_ld2Uzk6zH7yQ-9wJiQpMTSQbA&s',
            'price' => '$999',
            'description' => 'A high-performance laptop with 16GB RAM and 512GB SSD.'
        ],
        [
            'name' => 'Smartphone',
            'image' => 'https://m.media-amazon.com/images/I/51Nnp56PlbL._AC_SL1001_.jpg',
            'price' => '$699',
            'description' => 'A powerful smartphone with an amazing camera and battery life.'
        ],
        [
            'name' => 'Headphones',
            'image' => 'https://images.philips.com/is/image/philipsconsumer/b8992131d5a3401e9d6eb0c300d8f4fe?$pnglarge$&wid=700&hei=700',
            'price' => '$199',
            'description' => 'Wireless noise-canceling headphones for an immersive experience.'
        ],
    ];
    return view('products', ['products' => $products]);
});

Route::get('/calculator', function () {
    return view('calculator');
});

Route::get('/gpa_simulator', function () {
    $courses = [
        ['code' => 'CS101', 'title' => 'Project 2', 'credit' => 3],
        ['code' => 'CS102', 'title' => 'Digital Forensics', 'credit' => 4],
        ['code' => 'CS103', 'title' => 'linux and shell programming', 'credit' => 3],
        ['code' => 'CS104', 'title' => 'Network operation and managment', 'credit' => 3],
        ['code' => 'CS105', 'title' => 'Web and security technologies', 'credit' => 3],
    ];
    return view('gpa_simulator', ['courses' => $courses]);
});

Route::get('/product-catalog', function () {
    $products = [
        [
            'name' => 'LG TV 50"',
            'image' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSBn9aNIyombsgs60qE42IiL1mCGcS8hlG1_g&s', // Replace with actual image URL
            'price' => 500,
            'description' => 'A high-quality 50-inch LG TV with stunning display.',
        ],
        [
            'name' => 'Toshiba Refrigerator 14"',
            'image' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRD9uQarUrLMDHsKwaFh6jJXussOJhjM-nvsg&s', // Replace with actual image URL
            'price' => 750,
            'description' => 'A modern Toshiba refrigerator with spacious storage.',
        ],
    ];

    return view('productCatalog', compact('products'));
});







