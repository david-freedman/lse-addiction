<?php

use App\Applications\Http\Admin\Controllers\CourseController;
use App\Applications\Http\Customer\Controllers\CourseCatalogController;
use App\Applications\Http\Customer\Controllers\CustomerAuthController;
use App\Applications\Http\Customer\Controllers\CustomerDashboardController;
use App\Applications\Http\Customer\Controllers\CustomerProfileController;
use App\Applications\Http\Customer\Controllers\CustomerRegistrationController;
use App\Applications\Http\Customer\Controllers\MyCoursesController;
use App\Applications\Http\Customer\Controllers\PaymentController;
use App\Applications\Http\Customer\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CustomerDashboardController::class, 'index'])
    ->middleware(['auth', 'verified.customer'])
    ->name('home');

Route::prefix('customer')->name('customer.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('register', [CustomerRegistrationController::class, 'showRegistrationForm'])->name('register');
        Route::post('register', [CustomerRegistrationController::class, 'register']);

        Route::post('registration/send-code', [CustomerRegistrationController::class, 'sendVerificationCode'])->name('registration.send-code');
        Route::post('registration/verify-code', [CustomerRegistrationController::class, 'verifyVerificationCode'])->name('registration.verify-code');
        Route::post('registration/resend-code', [CustomerRegistrationController::class, 'resendVerificationCodeJson'])->name('registration.resend-code');

        Route::get('verify-phone', [CustomerRegistrationController::class, 'showVerifyPhone'])->name('verify-phone.show');
        Route::post('verify-phone', [CustomerRegistrationController::class, 'verifyPhone'])->name('verify-phone');

        Route::get('verify-email', [CustomerRegistrationController::class, 'showVerifyEmail'])->name('verify-email.show');
        Route::post('verify-email', [CustomerRegistrationController::class, 'verifyEmail'])->name('verify-email');

        Route::get('contact-details', [CustomerRegistrationController::class, 'showContactDetails'])->name('contact-details.show');
        Route::post('contact-details', [CustomerRegistrationController::class, 'saveContactDetails'])->name('contact-details');

        Route::get('profile-fields', [CustomerRegistrationController::class, 'showProfileFields'])->name('profile-fields.show');
        Route::post('profile-fields', [CustomerRegistrationController::class, 'saveProfileFields'])->name('profile-fields.save');
        Route::post('profile-fields/skip', [CustomerRegistrationController::class, 'skipProfileFields'])->name('profile-fields.skip');

        Route::post('resend-code', [CustomerRegistrationController::class, 'resendCode'])->name('resend-code');

        Route::get('login', [CustomerAuthController::class, 'showLoginForm'])->name('login');
        Route::post('login', [CustomerAuthController::class, 'sendLoginCode'])->name('login.send');

        Route::get('verify-login', [CustomerAuthController::class, 'showVerifyLogin'])->name('verify-login.show');
        Route::post('verify-login', [CustomerAuthController::class, 'verifyLogin'])->name('verify-login');
        Route::post('verify-login/resend', [CustomerAuthController::class, 'resendLoginCode'])->name('verify-login.resend');
    });

    Route::middleware('auth')->group(function () {
        Route::get('complete-verification', [CustomerAuthController::class, 'showCompleteVerification'])->name('complete-verification');
        Route::post('complete-verification', [CustomerAuthController::class, 'verifyCompleteVerification'])->name('complete-verification.verify');
        Route::post('complete-verification/resend', [CustomerAuthController::class, 'resendVerificationCode'])->name('complete-verification.resend');
    });

    Route::middleware(['auth', 'verified.customer'])->group(function () {
        Route::get('profile', [CustomerProfileController::class, 'show'])->name('profile.show');
        Route::get('profile/edit', [CustomerProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('profile', [CustomerProfileController::class, 'update'])->name('profile.update');
        Route::patch('profile/profile-fields', [CustomerProfileController::class, 'updateProfileFields'])->name('profile.profile-fields.update');

        Route::get('verify-change', [CustomerProfileController::class, 'showVerifyChange'])->name('verify-change.show');
        Route::post('verify-change', [CustomerProfileController::class, 'verifyChange'])->name('verify-change');
        Route::post('verify-change/resend', [CustomerProfileController::class, 'resendChangeCode'])->name('verify-change.resend');

        Route::post('logout', [CustomerAuthController::class, 'logout'])->name('logout');

        Route::get('my-courses', [MyCoursesController::class, 'index'])->name('my-courses');
        Route::get('courses/{course}', [MyCoursesController::class, 'show'])->name('courses.show');
        Route::post('courses/{course}/enroll', [MyCoursesController::class, 'enroll'])->name('courses.enroll');
        Route::post('courses/{course}/unenroll', [MyCoursesController::class, 'unenroll'])->name('courses.unenroll');

        Route::get('catalog', [CourseCatalogController::class, 'index'])->name('catalog.index');
        Route::get('catalog/{course}', [CourseCatalogController::class, 'show'])->name('catalog.show');
        Route::post('catalog/{course}/purchase', [CourseCatalogController::class, 'purchase'])->name('catalog.purchase');

        Route::get('transactions', [TransactionController::class, 'index'])->name('transactions.index');

        Route::get('payment/{transaction}/initiate', [PaymentController::class, 'initiate'])->name('payment.initiate');
        Route::post('payment/{transaction}/cancel', [PaymentController::class, 'cancel'])->name('payment.cancel');
    });

    Route::post('payment/callback', [PaymentController::class, 'callback'])->name('payment.callback');
    Route::any('payment/return', [PaymentController::class, 'return'])->name('payment.return');
});

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::resource('courses', CourseController::class);
});
