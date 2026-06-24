<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\CustomerLedgerController;
use App\Http\Controllers\CustomerPaymentController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
//print
Route::get('/customers/print', [CustomerController::class, 'print'])
    ->name('customers.print');
Route::get('/products/print', [ProductController::class, 'print'])
    ->name('products.print');
Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
Route::resource('customers', CustomerController::class);
Route::resource('products', ProductController::class);
Route::get(
    'sales/bulk-print',
    [SaleController::class, 'bulkPrint']
)->name('sales.bulk-print');
Route::resource('sales', SaleController::class);
Route::get('/customer-ledger', [CustomerLedgerController::class, 'index'])
    ->name('customer-ledger.index');
Route::get(
    '/customer-ledger/{customer}/bills',
    [CustomerLedgerController::class, 'bills']
)->name('customer-ledger.bills');
Route::post(
    '/customers/opening-balance',
    [CustomerController::class, 'storeOpeningBalance']
)->name('customers.opening-balance.store');
Route::get('/customer/{customer}/sales', [CustomerPaymentController::class, 'getCustomerSales']);
Route::post('/customer-payment/store',
    [CustomerPaymentController::class, 'store'])
    ->name('customer-payment.store');
Route::get('/sales/{sale}/print', [SaleController::class, 'print'])
    ->name('sales.print');
Route::prefix('reports')->group(function () {

Route::get('/product-wise', [ReportController::class, 'productWise'])
        ->name('reports.product-wise');
});
Route::get(
    '/customers/{customer}/balance',
    [SaleController::class, 'getBalance']
);
