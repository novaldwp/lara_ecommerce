<?php

use App\Http\Controllers\Admin\Payment\BankController;
use App\Http\Controllers\Admin\Payment\PaymentController;
use App\Http\Controllers\Admin\Payment\PaymentMethodController;
use App\Http\Controllers\Admin\Product\ProductController;
use App\Http\Controllers\Admin\Product\BrandController;
use App\Http\Controllers\Admin\Product\CategoryController;
use App\Http\Controllers\Admin\Product\WarrantyController;
use App\Http\Controllers\Front\AuthController;
use App\Http\Controllers\Front\CartController;
use App\Http\Controllers\Front\FrontProductController;
use App\Http\Controllers\Front\HomeController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('layouts.front.app');
// });

// E-COMMERCE SECTION
Route::group(['as' => 'ecommerce.'], function() {

    // ================================= AUTH ============================================
    // LOGIN & LOGOUT
    Route::get('/login', [AuthController::class, 'loginForm'])->name('login.index');
    Route::post('/login', [AuthController::class, 'postLogin'])->name('login.post');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

    // REGISTER
    Route::get('/register', [AuthController::class, 'registerForm'])->name('register.index');
    Route::post('/register', [AuthController::class, 'postRegister'])->name('register.post');
    Route::get('/verify/{token}', [AuthController::class, 'verifyEmail'])->name('register.verify');
    Route::get('/verification-success', [AuthController::class, 'verifySuccess'])->name('register.verify.success');
    Route::get('/verification-expired', [AuthController::class, 'verifyExpired'])->name('register.verify.expired');
    // Route::get();


    Route::get('/', [HomeController::class, 'index'])->name('index');
    Route::get('/{categoryParentSlug}/{categoryChildSlug}', [FrontProductController::class, 'getProductByCategory'])->name('product.category');
    Route::get('/{categoryParent}/{categoryChild}/{slug}', [ProductController::class, 'getDetailProduct'])->name('product.detail');
    Route::get('/products', [FrontProductController::class, 'getProductList'])->name('product.index');
    Route::get('/{brandSlug}', [FrontProductController::class, 'getProductByBrand'])->name('product.brand');

    Route::group(['middleware' => 'members'], function() {
        // CART
        Route::post('/cart/store', [CartController::class, 'add'])->name('cart.store');
    });
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

Route::group(['prefix' => 'secret'], function() {
    Route::get('/', function() {
        return route('login');
    });
    Auth::routes();
});

