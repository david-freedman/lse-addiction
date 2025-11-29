<?php

use App\Applications\Http\Student\Registration\Controllers\RegisterController;
use App\Applications\Http\Student\Registration\Controllers\ResendCodeController;
use App\Applications\Http\Student\Registration\Controllers\ResendVerificationCodeJsonController;
use App\Applications\Http\Student\Registration\Controllers\SaveContactDetailsController;
use App\Applications\Http\Student\Registration\Controllers\SaveProfileFieldsController;
use App\Applications\Http\Student\Registration\Controllers\SendVerificationCodeController;
use App\Applications\Http\Student\Registration\Controllers\ShowContactDetailsController;
use App\Applications\Http\Student\Registration\Controllers\ShowProfileFieldsController;
use App\Applications\Http\Student\Registration\Controllers\ShowRegistrationFormController;
use App\Applications\Http\Student\Registration\Controllers\ShowVerifyEmailController;
use App\Applications\Http\Student\Registration\Controllers\ShowVerifyPhoneController;
use App\Applications\Http\Student\Registration\Controllers\SkipProfileFieldsController;
use App\Applications\Http\Student\Registration\Controllers\VerifyEmailController;
use App\Applications\Http\Student\Registration\Controllers\VerifyPhoneController;
use App\Applications\Http\Student\Registration\Controllers\VerifyVerificationCodeController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('register', ShowRegistrationFormController::class)->name('register');
    Route::post('register', RegisterController::class);

    Route::post('registration/send-code', SendVerificationCodeController::class)->name('registration.send-code');
    Route::post('registration/verify-code', VerifyVerificationCodeController::class)->name('registration.verify-code');
    Route::post('registration/resend-code', ResendVerificationCodeJsonController::class)->name('registration.resend-code');

    Route::get('verify-phone', ShowVerifyPhoneController::class)->name('verify-phone.show');
    Route::post('verify-phone', VerifyPhoneController::class)->name('verify-phone');

    Route::get('verify-email', ShowVerifyEmailController::class)->name('verify-email.show');
    Route::post('verify-email', VerifyEmailController::class)->name('verify-email');

    Route::get('contact-details', ShowContactDetailsController::class)->name('contact-details.show');
    Route::post('contact-details', SaveContactDetailsController::class)->name('contact-details');

    Route::get('profile-fields', ShowProfileFieldsController::class)->name('profile-fields.show');
    Route::post('profile-fields', SaveProfileFieldsController::class)->name('profile-fields.save');
    Route::post('profile-fields/skip', SkipProfileFieldsController::class)->name('profile-fields.skip');

    Route::post('resend-code', ResendCodeController::class)->name('resend-code');
});
