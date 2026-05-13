<?php

use App\Http\Controllers\ProductController;

Route::get('/', [ProductController::class, 'index']);
Route::get('/products', [ProductController::class, 'list']);
Route::post('/products', [ProductController::class, 'store']);

?>