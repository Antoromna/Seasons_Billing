<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\CustomerLedgerController;
use App\Http\Controllers\CustomerPaymentController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TrayReturnController;
use App\Http\Controllers\CashSaleController;
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
Route::resource('tray-returns', TrayReturnController::class);
Route::get('/tray-returns-summary', [TrayReturnController::class, 'summary'])
    ->name('tray-returns.summary');
Route::get(
    '/tray-returns/bill-details/{billNo}',
    [TrayReturnController::class, 'billDetails']
);
Route::get(
    '/tray-returns/{customer}/ledger',
    [TrayReturnController::class, 'ledger']
)->name('tray-returns.ledger');
Route::get('/customer-ledger/print', [CustomerLedgerController::class, 'print'])
    ->name('customer-ledger.print');
Route::get('/customer-ledger/{customer}/print', [CustomerLedgerController::class, 'printLedger'])
    ->name('customer-ledger.print');

Route::get('/cash-sales/create', [CashSaleController::class, 'create'])
    ->name('cash_sales.create');

Route::post('/cash-sales', [CashSaleController::class, 'store'])
    ->name('cash_sales.store');

Route::get('/cash-sales/{sale}/edit', [CashSaleController::class, 'edit'])
    ->name('cash_sales.edit');

Route::put('/cash-sales/{sale}', [CashSaleController::class, 'update'])
    ->name('cash_sales.update');

Route::delete('/cash-sales/{sale}', [CashSaleController::class, 'destroy'])
    ->name('cash_sales.destroy');
Route::post('/tray-gives',[TrayReturnController::class, 'give']
    )->name('tray-gives.store');
Route::get('/dashboard/sales-overview', [HomeController::class, 'salesOverview'])
    ->name('dashboard.sales-overview');