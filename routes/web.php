<?php

use App\Http\Controllers\Admin\Product\ProductController;
use App\Http\Controllers\Admin\Product\BrandController;
use App\Http\Controllers\Admin\Product\CategoryController;
use App\Http\Controllers\Admin\Product\WarrantyController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['prefix' => 'admin'], function() {
    Route::group(['prefix' => 'manage-products'], function() {
        Route::resource('/products', ProductController::class)->except('show');
        Route::resource('/categories', CategoryController::class)->except('show');
        Route::resource('/brands', BrandController::class)->except('show');
        Route::resource('/warranties', WarrantyController::class)->except('show');

        Route::post('/products/save-image', [ProductController::class, 'storeProductImage'])->name('products.image');
    });

});


Auth::routes();


