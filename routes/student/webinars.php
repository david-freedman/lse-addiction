<?php

use App\Applications\Http\Student\Webinar\Controllers\GetMyWebinarsController;
use App\Applications\Http\Student\Webinar\Controllers\RegisterWebinarController;
use App\Applications\Http\Student\Webinar\Controllers\ShowWebinarController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified.student'])->group(function () {
    Route::get('/my-webinars', GetMyWebinarsController::class)->name('my-webinars');
    Route::get('/webinars/{webinar:slug}', ShowWebinarController::class)->name('webinar.show');
    Route::post('/webinars/{webinar:slug}/register', RegisterWebinarController::class)->name('webinar.register');
});
