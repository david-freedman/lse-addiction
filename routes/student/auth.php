<?php

use App\Applications\Http\Student\Auth\Controllers\EnrollAndRedirectController;
use App\Applications\Http\Student\Auth\Controllers\GoogleCallbackController;
use App\Applications\Http\Student\Auth\Controllers\GoogleRedirectController;
use App\Applications\Http\Student\Auth\Controllers\LogoutController;
use App\Applications\Http\Student\Auth\Controllers\ResendLoginCodeController;
use App\Applications\Http\Student\Auth\Controllers\ResendVerificationCodeController;
use App\Applications\Http\Student\Auth\Controllers\SendLoginCodeController;
use App\Applications\Http\Student\Auth\Controllers\ShowCompleteVerificationController;
use App\Applications\Http\Student\Auth\Controllers\ShowLoginFormController;
use App\Applications\Http\Student\Auth\Controllers\ShowVerifyLoginController;
use App\Applications\Http\Student\Auth\Controllers\VerifyCompleteVerificationController;
use App\Applications\Http\Student\Auth\Controllers\VerifyLoginController;
use Illuminate\Support\Facades\Route;

Route::get('enroll', EnrollAndRedirectController::class)->name('enroll');

Route::middleware('guest')->group(function () {
    Route::get('login', ShowLoginFormController::class)->name('login');
    Route::post('login', SendLoginCodeController::class)->name('login.send');

    Route::get('verify-login', ShowVerifyLoginController::class)->name('verify-login.show');
    Route::post('verify-login', VerifyLoginController::class)->name('verify-login');
    Route::post('verify-login/resend', ResendLoginCodeController::class)->name('verify-login.resend');

    Route::get('auth/google', GoogleRedirectController::class)->name('auth.google');
    Route::get('auth/google/callback', GoogleCallbackController::class)->name('auth.google.callback');
});

Route::middleware('auth')->group(function () {
    Route::get('complete-verification', ShowCompleteVerificationController::class)->name('complete-verification');
    Route::post('complete-verification', VerifyCompleteVerificationController::class)->name('complete-verification.verify');
    Route::post('complete-verification/resend', ResendVerificationCodeController::class)->name('complete-verification.resend');
});

Route::middleware(['auth', 'verified.student'])->group(function () {
    Route::post('logout', LogoutController::class)->name('logout');
});
