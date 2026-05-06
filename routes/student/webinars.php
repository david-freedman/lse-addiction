<?php

use App\Applications\Http\Student\Webinar\Controllers\GetMyWebinarsController;
use App\Applications\Http\Student\Webinar\Controllers\RegisterWebinarController;
use App\Applications\Http\Student\Webinar\Controllers\ShowWebinarController;
use App\Applications\Http\Student\Webinar\Controllers\ShowWebinarQuizController;
use App\Applications\Http\Student\Webinar\Controllers\SubmitWebinarQuizController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified.student'])->group(function () {
    Route::get('/my-webinars', GetMyWebinarsController::class)->name('my-webinars');
    Route::post('/webinars/{webinar:slug}/register', RegisterWebinarController::class)->name('webinar.register');

    Route::middleware(['profile.step.completed'])->group(function () {
        Route::get('/webinars/{webinar:slug}', ShowWebinarController::class)->name('webinar.show');

        Route::get('/webinars/{webinar:slug}/quiz', ShowWebinarQuizController::class)->name('webinar.quiz.show');
        Route::post('/webinars/{webinar:slug}/quiz', SubmitWebinarQuizController::class)->name('webinar.quiz.submit');
    });
});
