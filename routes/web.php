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
use App\Http\Controllers\Front\FrontProductDetailController;
use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\Front\MemberController;
use App\Http\Controllers\Front\OrderController;
use App\Http\Controllers\Front\PaymentController as FrontPaymentController;
use Illuminate\Support\Facades\Route;

// E-COMMERCE SECTION
Route::group(['prefix' => 'secret'], function() {
    Route::get('/', function() {
        return route('login');
    });
    Auth::routes();
});

Route::get('/testing', function() {
    return bcrypt("asdqwe");
});
Route::group(['as' => 'ecommerce.'], function() {

    // ================================= AUTH ============================================
    // LOGIN & LOGOUT
    Route::get('/login', [AuthController::class, 'loginForm'])->name('login.index');
    Route::post('/login', [AuthController::class, 'postLogin'])->name('login.post');

    // REGISTER
    Route::get('/register', [AuthController::class, 'registerForm'])->name('register.index');
    Route::post('/register', [AuthController::class, 'postRegister'])->name('register.post');
    Route::get('/verify/{token}', [AuthController::class, 'verifyEmail'])->name('register.verify');
    Route::get('/verification-success', [AuthController::class, 'verifySuccess'])->name('register.verify.success');
    Route::get('/verification-expired', [AuthController::class, 'verifyExpired'])->name('register.verify.expired');
    // Route::get();

    Route::group(['middleware' => 'members'], function() {
        Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
        // PROFILE
        Route::group(['prefix' => 'profile', 'as' => 'profile.'], function() {
            Route::get('/dashboard', [MemberController::class, 'index'])->name('index');

            Route::get('/orders', [OrderController::class, 'getOrderByMemberId'])->name('orders');
            Route::get('/orders/detail/{id}', [OrderController::class, 'getOrderByIdMember'])->name('orders.detail');

            Route::get('/account', [MemberController::class, 'account'])->name('account');
            Route::patch('/account/update-detail/{id}', [MemberController::class, 'updateDetailMember'])->name('account.detail.update');
            Route::patch('/account/update-password/{id}', [MemberController::class, 'updatePasswordMember'])->name('account.password.update');

            Route::get('/address', [MemberController::class, 'address'])->name('address');
            Route::get('/address/create', [MemberController::class, 'addAddress'])->name('address.create');
            Route::post('/address', [MemberController::class, 'storeAddress'])->name('address.store');
            Route::get('/address/{id}/edit', [MemberController::class, 'editAddress'])->name('address.edit');
            Route::patch('/address/{id}', [MemberController::class, 'updateAddress'])->name('address.update');
            Route::delete('/address/{id}', [MemberController::class, 'deleteAddress'])->name('address.delete');

        });
        // CART
        Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
        Route::post('/cart/check-out', [CartController::class, 'cartCheckOut'])->name('cart.checkout');
        Route::post('/cart/update', [CartController::class, 'updateCartItem'])->name('cart.update');
        Route::delete('/cart/delete/{id}', [CartController::class, 'deleteCartItem'])->name('cart.delete');
        // Route::get('/cart/check-out', [CartController::class, 'checkOutFromCart'])->name('cart.checkout.index');
        Route::group(['as' => 'product.'], function() {
            Route::post('/getActionCart', [FrontProductDetailController::class, 'getActionCart'])->name('detail.action');
            Route::post('/cart/store', [FrontProductDetailController::class, 'addToCart'])->name('detail.store');
        });

        // ORDER
        Route::group(['as' => 'order.', 'prefix' => 'order'], function() {
            Route::post('/coba', [OrderController::class, 'index'])->name('index');
            Route::post('/store', [OrderController::class, 'createOrder'])->name('store');
            // Route::get('/get-status-order/{id}', []);
        });

        // PAYMENT
        Route::group(['as' => 'payment.', 'prefix' => 'payment'], function() {
            Route::get('/coba', [FrontPaymentController::class, 'index'])->name('index');
            Route::get('/get-payment-order/{id}', [FrontPaymentController::class, 'getPaymentFromOrderId'])->name('order');
            Route::get('/get-payment-status/{id}', [FrontPaymentController::class, 'getPaymentStatusOrderId'])->name('status');
            Route::get('get-payment-detail/{code}', [FrontPaymentController::class, 'getPaymentDetail'])->name('detail');
            // Route::post('/notif-confirm', [FrontPaymentController::class, 'notifConfirm']);
        });
    });

    Route::post('payment/notification', [FrontPaymentController::class, 'notification']);
    Route::get('payment/completed', [FrontPaymentController::class, 'completed']);
    Route::get('/', [HomeController::class, 'index'])->name('index');
    Route::get('/product/{categoryParentSlug}/{categoryChildSlug}', [FrontProductController::class, 'getProductByCategory'])->name('product.category');
    Route::get('/product/{categoryParent}/{categoryChild}/{slug}', [ProductController::class, 'getDetailProduct'])->name('product.detail');
    Route::get('/products', [FrontProductController::class, 'getProductList'])->name('product.index');
    Route::get('/brand/{brandSlug}', [FrontProductController::class, 'getProductByBrand'])->name('product.brand');
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

