<?php

use Illuminate\Http\Request;

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

Route::pattern('digit', '[0-9]+');
Route::pattern('alpha', '[a-z]+');
Route::pattern('alnum', '[0-9a-z]+');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('api')->namespace('Admin')->group(function () {
    Route::apiResource('regions', 'RegionController');

    Route::apiResource('products', 'ProductController');
    Route::apiResource('vendors', 'VendorController');

    Route::apiResource('purchases', 'PurchaseController');

    Route::apiResource('/purchases/{id}/products', 'PurchaseProductController');

    Route::get('analysis', 'AnalysisController@index');
});
