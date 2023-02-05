<?php

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['as' => 'api.'], function () {
    Route::group(['as' => 'items.', 'prefix' => 'items'], function () {
        Route::apiResource('items', App\Http\Controllers\Api\Items\Items::class);
        Route::apiResource('detail-groups', App\Http\Controllers\Api\Items\DetailGroups::class);
    });

    Route::apiResource('companies', App\Http\Controllers\Api\Companies::class);
});