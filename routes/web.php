<?php

use App\Http\Controllers\Admin\Product\ProductController;
use App\Http\Controllers\Admin\Product\BrandController;
use App\Http\Controllers\Admin\Product\CategoryController;
use App\Http\Controllers\Admin\Product\OptionController;
use App\Http\Controllers\Admin\Product\OptionValueController;
use App\Http\Controllers\Admin\Product\WarrantyController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['prefix' => 'admin'], function() {
    Route::group(['prefix' => 'manage-products'], function() {
        Route::resource('/categories', CategoryController::class)->except('show');
        Route::resource('/brands', BrandController::class)->except('show');
        Route::resource('/warranties', WarrantyController::class)->except('show');
        Route::resource('/options', OptionController::class)->except('show');
        Route::resource('/option-values', OptionValueController::class)->except('show');
    });

});


Auth::routes();


