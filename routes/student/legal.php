<?php

use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::view('privacy-policy', 'student.legal.privacy-policy')->name('privacy-policy');
    Route::view('public-offer', 'student.legal.public-offer')->name('public-offer');
});
