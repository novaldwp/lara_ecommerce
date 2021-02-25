<?php

use App\Http\Controllers\Admin\Product\CategoryController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['prefix' => 'admin'], function() {
    Route::group(['prefix' => 'manage-products'], function() {
        Route::resource('/categories', CategoryController::class)->except('show');
    });

});


Auth::routes();


