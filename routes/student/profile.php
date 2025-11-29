<?php

use App\Applications\Http\Student\Profile\Controllers\EditProfileController;
use App\Applications\Http\Student\Profile\Controllers\ResendChangeCodeController;
use App\Applications\Http\Student\Profile\Controllers\ShowProfileController;
use App\Applications\Http\Student\Profile\Controllers\ShowVerifyChangeController;
use App\Applications\Http\Student\Profile\Controllers\UpdateProfileController;
use App\Applications\Http\Student\Profile\Controllers\UpdateProfileFieldsController;
use App\Applications\Http\Student\Profile\Controllers\VerifyChangeController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified.student'])->group(function () {
    Route::get('profile', ShowProfileController::class)->name('profile.show');
    Route::get('profile/edit', EditProfileController::class)->name('profile.edit');
    Route::patch('profile', UpdateProfileController::class)->name('profile.update');
    Route::patch('profile/profile-fields', UpdateProfileFieldsController::class)->name('profile.profile-fields.update');

    Route::get('verify-change', ShowVerifyChangeController::class)->name('verify-change.show');
    Route::post('verify-change', VerifyChangeController::class)->name('verify-change');
    Route::post('verify-change/resend', ResendChangeCodeController::class)->name('verify-change.resend');
});
