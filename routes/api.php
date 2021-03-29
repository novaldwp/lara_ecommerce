<?php

use App\Http\Controllers\Front\CartController;
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
Route::get('/get-city', [CartController::class, 'getCity']);
Route::get('/get-city/selected-province', [CartController::class, 'getCitySelectedProvince']);
// Route::get('/get-shipping-cost', [CartController::class, 'getShippingCost']);
Route::get('/test', [CartController::class, 'getShippingCost']);
