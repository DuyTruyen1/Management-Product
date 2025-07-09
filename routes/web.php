<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('products', [ProductController::class, 'index'])->name('products.index');           // GET /products
Route::get('products/create', [ProductController::class, 'create'])->name('products.create');   // GET /products/create
Route::post('products', [ProductController::class, 'store'])->name('products.store');           // POST /products
Route::get('products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit'); // GET /products/{id}/edit
Route::put('products/{product}', [ProductController::class, 'update'])->name('products.update'); // PUT /products/{id}
Route::delete('products/{product}', [ProductController::class, 'destroy'])->name('products.destroy'); // DELETE /products/{id}
