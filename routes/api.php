<?php

use Illuminate\Support\Facades\Route;
use App\Models\Product; // Essential: Don't forget to import Product!

Route::get('/product', function () {
    $products = Product::all();
    return response()->json($products);
});
