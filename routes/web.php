<?php

use Illuminate\Support\Facades\Route;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\WeightLogController; 

Route::view('/about-me', 'about-me')->name('about-me');
Route::view('/welcome', 'welcome')->name('welcome');


// 1. Raw SQL Query Route
Route::get('query/sql', function () {
    $products = DB::select("SELECT * FROM products");
    return view('query-test', compact('products'));
});

// 2. Query Builder Route
Route::get('query/builder', function () {
    $products = DB::table('products')->get();
    return view('query-test', compact('products'));
});

// 3. Eloquent ORM Route
Route::get('query/orm', function () {
    $products = Product::get();
    return view('query-test', compact('products'));
});

// Helper Route
Route::get('product/form', function () {
    // Left empty as placeholder
})->name("product.form");



Route::get('barchart', function () {
    return view('barchart');
})->name('barchart');


Route::get('/', function () {
    return redirect()->route('weights.index');
});

Route::resource('weights', WeightLogController::class)->except(['create', 'show', 'edit']);
