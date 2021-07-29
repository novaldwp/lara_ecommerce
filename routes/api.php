<?php

use App\Http\Controllers\Front\CartController;
use App\Http\Controllers\Front\OrderController;
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

Route::post('/cart/selected-item', [CartController::class, 'getGrandTotalSelectedItem']);
Route::post('/cart/updateCartPlusQty', [CartController::class, 'updateCartPlusQty']);
Route::post('/cart/updateCartMinusQty', [CartController::class, 'updateCartMinusQty']);

Route::get('/get-city', [OrderController::class, 'getCity']);
Route::get('/get-city/selected-province', [OrderController::class, 'getCitySelectedProvince']);
// Route::get('/get-shipping-cost', [OrderController::class, 'getShippingCost']);
Route::get('/test', [OrderController::class, 'getShippingCost']);
Route::post('/order/set-order', [CartController::class, 'setOrderProduct']);
