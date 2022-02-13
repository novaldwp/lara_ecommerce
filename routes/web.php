<?php

use App\Http\Controllers\Admin\Analisis\DataTrainingController;
use App\Http\Controllers\Admin\Analisis\NegativeWordController;
use App\Http\Controllers\Admin\Analisis\PositiveWordController;
use App\Http\Controllers\Admin\Analisis\SentimenController;
use App\Http\Controllers\Admin\Payment\PaymentController;
use App\Http\Controllers\Admin\Product\ProductController;
use App\Http\Controllers\Admin\Product\BrandController;
use App\Http\Controllers\Admin\Product\CategoryController;
use App\Http\Controllers\Front\AuthController;
use App\Http\Controllers\Front\CartController;
use App\Http\Controllers\Front\FrontProductController;
use App\Http\Controllers\Front\FrontProductDetailController;
use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Front\PaymentController as FrontPaymentController;
use App\Http\Controllers\main\UserController;
use App\Http\Controllers\Admin\Main\DashboardController;
use App\Http\Controllers\Admin\Product\ReviewController;
use App\Http\Controllers\Admin\Setting\ProfileController;
use App\Http\Controllers\Admin\Setting\SliderController;
use App\Http\Controllers\Analisis\SentimentAnalysisController;
use App\Services\PaymentService;
use Illuminate\Support\Facades\Route;
use Faker\Factory as Faker;

// E-COMMERCE SECTION
Route::group(['as' => 'auth.'], function() {

    // REGISTER
    Route::group(['middleware' => 'guest'], function() {
        // LOGIN & LOGOUT
        Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AuthController::class, 'userLogin'])->name('login.post');
        //REGISTER
        Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
        Route::post('/register', [AuthController::class, 'userRegister'])->name('register.store');
        // Route::post('/register', [AuthController::class, 'postRegister'])->name('register.post');
        Route::get('/verify/{token}', [AuthController::class, 'verifyEmail'])->name('register.verify');
        Route::get('/verification-success', [AuthController::class, 'verifySuccess'])->name('register.verify.success');
        Route::get('/verification-expired', [AuthController::class, 'verifyExpired'])->name('register.verify.expired');
    });

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::group(['as' => 'ecommerce.'], function() {
    Route::group(['middleware' => 'role:customer'], function() {
        // PROFILE
        Route::group(['prefix' => 'profile', 'as' => 'profile.'], function() {
            Route::get('/dashboard', [UserController::class, 'showDashboardCustomer'])->name('index');

            Route::get('/orders', [OrderController::class, 'getOrderByUserId'])->name('orders');
            Route::get('/orders/detail/{order_id}', [OrderController::class, 'getOrderDetailByIdUserId'])->name('orders.detail');

            Route::get('/account', [UserController::class, 'showCustomerProfileForm'])->name('account');
            Route::patch('/account/update-detail/{id}', [UserController::class, 'updateUserDetail'])->name('account.detail.update');
            Route::patch('/account/update-password/{id}', [UserController::class, 'updateUserPassword'])->name('account.password.update');
        });
        // CART
        Route::get('/carts', [CartController::class, 'index'])->name('cart.index');
        Route::get('/carts/add-product-to-cart/{product_id}', [CartController::class, 'addProductToCart']);
        Route::get('/carts/increaseProductAmountByCartId/{cart_id}', [CartController::class, 'increaseProductAmountByCartId']);
        Route::get('/carts/decreaseProductAmountByCartId/{cart_id}', [CartController::class, 'decreaseProductAmountByCartId']);
        Route::post('/carts/selected-item', [CartController::class, 'getCartSelectedProduct']);
        Route::delete('/carts/delete/{id}', [CartController::class, 'deleteCartProductById'])->name('cart.delete');
        Route::group(['as' => 'product.'], function() {
            Route::post('/getActionCart', [FrontProductDetailController::class, 'getActionCart'])->name('detail.action');
            Route::post('/carts/store', [FrontProductDetailController::class, 'addToCart'])->name('detail.store');
        });

        // ORDER
        Route::group(['as' => 'orders.', 'prefix' => 'orders'], function() {
            Route::post('/store', [OrderController::class, 'createOrder'])->name('store');
            Route::post('/check-out', [OrderController::class, 'CheckOut'])->name('checkout');
        });

        Route::post('/reviews/create', [ReviewController::class, 'create'])->name('reviews.create');
        // PAYMENT
        Route::group(['as' => 'payment.', 'prefix' => 'payment'], function() {
            Route::get('/coba', [FrontPaymentController::class, 'index'])->name('index');
            Route::get('/get-payment-order/{id}', [FrontPaymentController::class, 'getPaymentFromOrderId'])->name('order');
            Route::get('/get-payment-status/{id}', [FrontPaymentController::class, 'getPaymentStatusOrderId'])->name('status');
            Route::get('get-payment-detail/{code}', [FrontPaymentController::class, 'getPaymentDetail'])->name('detail');
        });
    });

    Route::post('payment/notification', [FrontPaymentController::class, 'notification']);
    Route::get('payment/completed', [FrontPaymentController::class, 'completed']);
    // Route::
    Route::get('/', [HomeController::class, 'index'])->name('index');
    Route::get('/product/{categoryParentSlug}/{categoryChildSlug}', [FrontProductController::class, 'getProductByCategory'])->name('product.category');
    Route::get('/product/{categoryParent}/{categoryChild}/{slug}', [ProductController::class, 'getDetailProduct'])->name('product.detail');
    Route::get('/products', [FrontProductController::class, 'getProductList'])->name('product.index');
    Route::get('/products/q', [ProductController::class, 'getProductByName'])->name('product.search');
    Route::get('/brands/{brandSlug}', [FrontProductController::class, 'getProductByBrand'])->name('product.brand');
});


Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth', 'role:admin|owner']], function() {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::group(['prefix' => 'manage-products'], function() {
        Route::resource('/products', ProductController::class)->except('show');
        Route::get('/products/restore/{id}', [ProductController::class, 'restore'])->name('products.restore');
        Route::get('/products/detail/{id}', [Productcontroller::class, 'showDetailProduct'])->name('products.detail');
        Route::resource('/categories', CategoryController::class)->except('show');
        Route::get('/categories/restore/{id}', [CategoryController::class, 'restore'])->name('categories.restore');
        Route::resource('/brands', BrandController::class)->except('show');
        Route::get('/brands/restore/{id}', [BrandController::class, 'restore'])->name('brands.restore');

        Route::post('/products/save-image', [ProductController::class, 'storeProductImage'])->name('products.image');
        Route::get('/review-by-product-id/{id}', [ReviewController::class, 'getReviewByProductId'])->name('reviews.product');
    });

    Route::group(['prefix' => 'manage-users'], function() {
        Route::get('/customers', [UserController::class, 'getAllCustomers'])->name('customers.index');
        Route::get('/customers/{user_id}', [UserController::class, 'getUserById'])->name('customers.detail');

        Route::get('/admins', [UserController::class, 'getAllAdmins'])->name('admins.index');
        Route::get('/admins/create', [UserController::class, 'showCreateAdminForm'])->name('admins.create');
        Route::post('/admins', [UserController::class, 'store'])->name('admins.store');
        Route::get('/admins/delete/{id}', [UserController::class, 'deleteAdmin'])->name('admins.delete');
    });

    Route::group(['prefix' => 'manage-payments'], function() {
        Route::resource('/payments', PaymentController::class)->except('show');
    });

    Route::group(['prefix' => 'manage-orders', 'as' => 'orders.'], function() {
        // Route::get();
        Route::get('/orders', [OrderController::class, 'getAllOrders'])->name('index');
        Route::get('/orders/detail/{order_id}', [OrderController::class, 'getOrderById'])->name('detail');
        Route::get('/orders/receive/{order_id}', [OrderController::class, 'setOrderReceive'])->name('receive');
        Route::get('/orders/complete/{order_id}', [OrderController::class, 'setOrderComplete'])->name('complete');
        Route::post('/orders/delivery', [OrderController::class, 'setOrderDelivery'])->name('delivery');
        Route::post('/orders/cancel', [OrderController::class, 'setOrderCancel'])->name('reject');
        Route::get('orders/get-order-by-customer/{user_id}', [OrderController::class, 'getOrderByCustomer'])->name('customer');
    });

    Route::group(['prefix' => 'manage-analyst', 'as' => 'analyst.'], function() {
        Route::get('/sentimen-analysistt', [SentimenController::class, 'index'])->name('sentimen');
        Route::resource('/sentiment-analyses', SentimentAnalysisController::class)->only(['index']);
        Route::resource('/data-trainings', DataTrainingController::class)->except(['show']);
        Route::get('/data-trainings/restore/{id}', [DataTrainingController::class, 'restore']);
        Route::post('/data-trainings/import', [DataTrainingController::class, 'import']);
        Route::get('/data-trainings/export', [DataTrainingController::class, 'export'])->name('data-trainings.export');
        Route::resource('/positive-words', PositiveWordController::class)->except(['show']);
        Route::get('/positive-words/restore/{id}', [PositiveWordController::class, 'restore']);
        Route::post('/positive-words/import', [PositiveWordController::class, 'import']);
        Route::get('/positive-words/export', [PositiveWordController::class, 'export'])->name('positive-words.export');
        Route::resource('/negative-words', NegativeWordController::class)->except(['show']);
        Route::get('/negative-words/restore/{id}', [NegativeWordController::class, 'restore']);
        Route::post('/negative-words/import', [NegativeWordController::class, 'import']);
        Route::get('/negative-words/export', [NegativeWordController::class, 'export'])->name('negative-words.export');
    });

    Route::group(['prefix' => 'manage-reports', 'as' => 'report.'], function() {
        Route::get('/orders', [OrderController::class, 'getOrderReport'])->name('orders');
        Route::get('/products', [ProductController::class, 'getProductReport'])->name('products');
        Route::get('/customers', [UserController::class, 'getCustomerReport'])->name('customers');
        Route::get('/analysis', [SentimenController::class, 'getAnalysisReport'])->name('analysis');
    });

    Route::group(['prefix' => 'manage-settings', 'as' => 'setting.'], function() {
        Route::get('/profiles', [ProfileController::class, 'getProfile'])->name('profile.index');
        Route::post('/profiles', [ProfileController::class, 'storeProfile'])->name('profile.store');
        Route::resource('/sliders', SliderController::class)->except(['show']);
        Route::get('/sliders/restore/{id}', [SliderController::class, 'restore'])->name('sliders.restore');
    });
});

