<?php

use Illuminate\Support\Facades\Route;

Route::get('', function () {
    // return view('welcome');

    return redirect()->route('login');
})->name('home');

Auth::routes();

Route::middleware(["auth"])->group(function () {
    Route::resource('dashboards', App\Http\Controllers\HomeController::class)->only('index');

    Route::resource('users', App\Http\Controllers\UserController::class);

    Route::resource('operation-types', App\Http\Controllers\OperationTypeController::class);

    Route::group(['as' => 'items.', 'prefix' => 'items'], function () {
        Route::resource('categories', App\Http\Controllers\CategoryController::class);
        Route::resource('unit-of-measurements', App\Http\Controllers\UnitOfMeasurementController::class);
        Route::resource('items', App\Http\Controllers\ItemController::class);
    });

    Route::resource('operating-costs', App\Http\Controllers\OperatingCostController::class);

    Route::group(['as' => 'payments.', 'prefix' => 'payments'], function () {
        Route::resource('banks', App\Http\Controllers\BankController::class);
        Route::resource('payment-methods', App\Http\Controllers\PaymentMethodController::class);
        Route::resource('bank-accounts', App\Http\Controllers\BankAccountController::class);
        Route::resource('payment-terms', App\Http\Controllers\PaymentTermController::class);
    });
});
