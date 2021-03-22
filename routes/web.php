<?php

use App\Http\Controllers\Admin\Payment\BankController;
use App\Http\Controllers\Admin\Payment\PaymentController;
use App\Http\Controllers\Admin\Payment\PaymentMethodController;
use App\Http\Controllers\Admin\Product\ProductController;
use App\Http\Controllers\Admin\Product\BrandController;
use App\Http\Controllers\Admin\Product\CategoryController;
use App\Http\Controllers\Admin\Product\WarrantyController;
use App\Http\Controllers\Front\FrontProductController;
use App\Http\Controllers\Front\HomeController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('layouts.front.app');
// });

// E-COMMERCE SECTION
Route::group(['as' => 'ecommerce.'], function() {

    Route::get('/', [HomeController::class, 'index'])->name('index');
    Route::get('/{categoryParentSlug}/{categoryChildSlug}', [FrontProductController::class, 'getProductByCategory'])->name('product.category');
    Route::get('/{categoryParent}/{categoryChild}/{slug}', [ProductController::class, 'getDetailProduct'])->name('product.detail');
    Route::get('/products', [FrontProductController::class, 'getProductList'])->name('product.index');
    Route::get('/{brandSlug}', [FrontProductController::class, 'getProductByBrand'])->name('product.brand');
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

    Route::group(['prefix' => 'manage-payment'], function() {
        Route::resource('/banks', BankController::class)->except('show');
        Route::resource('/payments', PaymentController::class)->except('show');
        Route::resource('/payment-methods', PaymentMethodController::class)->except('show');
    });
});


Auth::routes();


