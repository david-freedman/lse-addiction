<?php

use App\Applications\Http\Admin\Transaction\Controllers\ExportTransactionsController;
use App\Applications\Http\Admin\Transaction\Controllers\GetFinancesController;
use App\Applications\Http\Admin\Transaction\Controllers\GetTransactionsListController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:admin', 'verified.user', 'role:admin'])->group(function () {
    Route::prefix('finances')->name('finances.')->group(function () {
        Route::get('/', GetFinancesController::class)->name('index');
        Route::get('/transactions', GetTransactionsListController::class)->name('transactions');
        Route::get('/transactions/export', ExportTransactionsController::class)->name('transactions.export');
    });
});
