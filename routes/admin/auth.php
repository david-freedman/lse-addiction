<?php

use App\Applications\Http\Admin\Auth\Controllers\LogoutController;
use App\Applications\Http\Admin\Auth\Controllers\ResendLoginCodeController;
use App\Applications\Http\Admin\Auth\Controllers\SendLoginCodeController;
use App\Applications\Http\Admin\Auth\Controllers\ShowLoginFormController;
use App\Applications\Http\Admin\Auth\Controllers\ShowVerifyLoginController;
use App\Applications\Http\Admin\Auth\Controllers\VerifyLoginController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest:admin')->group(function () {
    Route::get('/login', ShowLoginFormController::class)
        ->name('login');
    Route::post('/login', SendLoginCodeController::class)
        ->middleware('throttle:5,1')
        ->name('login.send');
    Route::get('/verify-login', ShowVerifyLoginController::class)
        ->name('verify-login.show');
    Route::post('/verify-login', VerifyLoginController::class)
        ->middleware('throttle:5,1')
        ->name('verify-login');
    Route::post('/verify-login/resend', ResendLoginCodeController::class)
        ->middleware('throttle:3,1')
        ->name('verify-login.resend');
});

Route::middleware(['auth:admin', 'verified.user'])->group(function () {
    Route::post('/logout', LogoutController::class)
        ->name('logout');
});
