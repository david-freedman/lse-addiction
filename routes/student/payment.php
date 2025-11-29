<?php

use App\Applications\Http\Student\Payment\Controllers\CancelPaymentController;
use App\Applications\Http\Student\Payment\Controllers\InitiatePaymentController;
use App\Applications\Http\Student\Payment\Controllers\ProcessPaymentCallbackController;
use App\Applications\Http\Student\Payment\Controllers\ShowPaymentReturnController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified.student'])->group(function () {
    Route::get('payment/{transaction}/initiate', InitiatePaymentController::class)->name('payment.initiate');
    Route::post('payment/{transaction}/cancel', CancelPaymentController::class)->name('payment.cancel');
});

Route::post('payment/callback', ProcessPaymentCallbackController::class)->name('payment.callback');
Route::any('payment/return', ShowPaymentReturnController::class)->name('payment.return');
