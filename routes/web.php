<?php

use Illuminate\Support\Facades\Route;

Route::get('', function () {
    // return view('welcome');

    return redirect()->route('login');
})->name('home');

Auth::routes();

Route::middleware(["auth"])->group(function () {
    Route::resource('dashboards', App\Http\Controllers\HomeController::class)->only(['index']);

    Route::resource('billings.items', App\Http\Controllers\BillingItemController::class);
    Route::put('billings/{operating_cost_transaction}/status', [App\Http\Controllers\BillingController::class, 'updateStatus'])->name('billings.status');
    Route::resource('billings', App\Http\Controllers\BillingController::class);

    Route::resource('purchase-orders.items', App\Http\Controllers\PurchaseOrderItemController::class);
    Route::put('purchase-orders/{purchase_order}/status', [App\Http\Controllers\PurchaseOrderController::class, 'updateStatus'])->name('purchase-orders.status');
    Route::resource('purchase-orders', App\Http\Controllers\PurchaseOrderController::class);

    Route::resource('invoices.items', App\Http\Controllers\InvoiceItemController::class);
    Route::put('invoices/{operating_cost_transaction}/status', [App\Http\Controllers\InvoiceController::class, 'updateStatus'])->name('invoices.status');
    Route::resource('invoices', App\Http\Controllers\InvoiceController::class);

    Route::resource('sales-orders.items', App\Http\Controllers\SalesOrderItemController::class);
    Route::put('sales-orders/{sales_order}/status', [App\Http\Controllers\SalesOrderController::class, 'updateStatus'])->name('sales-orders.status');
    Route::resource('sales-orders', App\Http\Controllers\SalesOrderController::class);

    Route::resource('operating-cost-transactions.details', App\Http\Controllers\OperatingCostTransactionDetailController::class);
    Route::put('operating-cost-transactions/{operating_cost_transaction}/status', [App\Http\Controllers\OperatingCostTransactionController::class, 'updateStatus'])->name('operating-cost-transactions.status');
    Route::resource('operating-cost-transactions', App\Http\Controllers\OperatingCostTransactionController::class);

    Route::resource('monthly-journals', App\Http\Controllers\MonthlyJournalController::class)->only(['index', 'show']);
    Route::resource('storage-operation-types', App\Http\Controllers\StorageOperationTypeController::class)->only(['index', 'show', 'edit', 'update']);

    Route::group(['as' => 'items.', 'prefix' => 'items'], function () {
        Route::resource('categories', App\Http\Controllers\CategoryController::class);
        Route::resource('unit-of-measurements', App\Http\Controllers\UnitOfMeasurementController::class);
        Route::resource('items', App\Http\Controllers\ItemController::class);
    });

    Route::resource('operating-costs', App\Http\Controllers\OperatingCostController::class);

    Route::group(['as' => 'payments.', 'prefix' => 'payments'], function () {
        Route::resource('payment-methods', App\Http\Controllers\PaymentMethodController::class);
        Route::resource('bank-accounts', App\Http\Controllers\BankAccountController::class);
        Route::resource('payment-terms', App\Http\Controllers\PaymentTermController::class);
    });

    Route::resource('users', App\Http\Controllers\UserController::class);

    Route::resource('statuses', App\Http\Controllers\StatusController::class);
    Route::resource('owner-types', App\Http\Controllers\OwnerTypeController::class);
    Route::resource('operation-types', App\Http\Controllers\OperationTypeController::class);
    Route::resource('banks', App\Http\Controllers\BankController::class);
});
