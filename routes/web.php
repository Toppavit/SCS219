<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WeightLogController;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('weights.index');
});

Route::view('/about-me', 'about-me')->name('about-me');
Route::view('/welcome', 'welcome')->name('welcome');

// EP02 Hero
Route::view('/gallery', 'gallery')->name('gallery');

// EP03 Active Bootstrap
Route::prefix('active')->group(function () {
    Route::view('/index', 'active.index')->name('index');
    Route::view('/about', 'active.about')->name('about');
    Route::view('/services', 'active.services')->name('services');
    Route::view('/portfolio', 'active.portfolio')->name('portfolio');
    Route::view('/team', 'active.team')->name('team');
    Route::view('/blog', 'active.blog')->name('blog');
    Route::view('/contact', 'active.contact')->name('contact');
});

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

// Breeze sends users here after login
Route::get('/dashboard', function () {
    return redirect()->route('weights.index');
})->middleware('auth')->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::resource('weights', WeightLogController::class)->except(['create', 'show', 'edit']);

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
