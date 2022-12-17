<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('', function () {
    // return view('welcome');

    return redirect()->route('login');
})->name('home');

Auth::routes();

Route::middleware(["auth"])->group(function () {
    Route::resource('dashboards', App\Http\Controllers\HomeController::class)->only('index');

    Route::resource('users', App\Http\Controllers\UserController::class);

    Route::resource('item-categories', App\Http\Controllers\ItemCategoryController::class);
    Route::resource('unit-of-measurements', App\Http\Controllers\ItemCategoryController::class);
    Route::resource('items', App\Http\Controllers\ItemController::class);
});
