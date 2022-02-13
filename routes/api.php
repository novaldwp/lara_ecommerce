<?php

use App\Http\Controllers\Front\CartController;
use App\Http\Controllers\Admin\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/cart/add-product-to-cart/{id}', [CartController::class, 'addCart']);

Route::get('/get-city', [OrderController::class, 'getCity']);
Route::get('/get-city/selected-province', [OrderController::class, 'getCitySelectedProvince']);
Route::get('/get-shipping-cost', [OrderController::class, 'getShippingCost']);
Route::post('/order/set-order', [CartController::class, 'setOrderProduct']);
