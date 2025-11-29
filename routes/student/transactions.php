<?php

use App\Applications\Http\Student\Transaction\Controllers\GetTransactionsController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified.student'])->group(function () {
    Route::get('transactions', GetTransactionsController::class)->name('transactions.index');
});
