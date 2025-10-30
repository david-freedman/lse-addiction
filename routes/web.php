<?php

use App\Applications\Http\Controllers\CustomerAuthController;
use App\Applications\Http\Controllers\CustomerProfileController;
use App\Applications\Http\Controllers\CustomerRegistrationController;
use App\Applications\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::prefix('customer')->name('customer.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('register', [CustomerRegistrationController::class, 'showRegistrationForm'])->name('register');
        Route::post('register', [CustomerRegistrationController::class, 'register']);

        Route::get('verify-phone', [CustomerRegistrationController::class, 'showVerifyPhone'])->name('verify-phone.show');
        Route::post('verify-phone', [CustomerRegistrationController::class, 'verifyPhone'])->name('verify-phone');

        Route::get('verify-email', [CustomerRegistrationController::class, 'showVerifyEmail'])->name('verify-email.show');
        Route::post('verify-email', [CustomerRegistrationController::class, 'verifyEmail'])->name('verify-email');

        Route::get('login', [CustomerAuthController::class, 'showLoginForm'])->name('login');
        Route::post('login', [CustomerAuthController::class, 'sendLoginCode'])->name('login.send');

        Route::get('verify-login', [CustomerAuthController::class, 'showVerifyLogin'])->name('verify-login.show');
        Route::post('verify-login', [CustomerAuthController::class, 'verifyLogin'])->name('verify-login');
    });

    Route::middleware('auth')->group(function () {
        Route::get('profile', [CustomerProfileController::class, 'show'])->name('profile.show');
        Route::get('profile/edit', [CustomerProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('profile', [CustomerProfileController::class, 'update'])->name('profile.update');

        Route::get('verify-change', [CustomerProfileController::class, 'showVerifyChange'])->name('verify-change.show');
        Route::post('verify-change', [CustomerProfileController::class, 'verifyChange'])->name('verify-change');

        Route::post('logout', [CustomerAuthController::class, 'logout'])->name('logout');
    });
});
