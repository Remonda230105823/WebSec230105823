<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\ProductsController;
use App\Http\Controllers\Web\UsersController;

Route::get('register', [UsersController::class, 'register'])->name('register');
Route::post('register', [UsersController::class, 'doRegister'])->name('do_register');
Route::get('login', [UsersController::class, 'login'])->name('login');
Route::post('login', [UsersController::class, 'doLogin'])->name('do_login');
Route::get('logout', [UsersController::class, 'doLogout'])->name('do_logout');
Route::get('users', [UsersController::class, 'list'])->name('users');
Route::get('profile/{user?}', [UsersController::class, 'profile'])->name('profile');
Route::get('users/edit/{user?}', [UsersController::class, 'edit'])->name('users_edit');
Route::post('users/save/{user}', [UsersController::class, 'save'])->name('users_save');
Route::get('users/delete/{user}', [UsersController::class, 'delete'])->name('users_delete');
Route::get('users/edit_password/{user?}', [UsersController::class, 'editPassword'])->name('edit_password');
Route::post('users/save_password/{user}', [UsersController::class, 'savePassword'])->name('save_password');



Route::get('products', [ProductsController::class, 'list'])->name('products_list');
Route::get('products/edit/{product?}', [ProductsController::class, 'edit'])->name('products_edit');
Route::post('products/save/{product?}', [ProductsController::class, 'save'])->name('products_save');
Route::get('products/delete/{product}', [ProductsController::class, 'delete'])->name('products_delete');



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



