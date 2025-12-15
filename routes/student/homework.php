<?php

use App\Applications\Http\Student\Homework\Controllers\DownloadFileController;
use App\Applications\Http\Student\Homework\Controllers\SubmitHomeworkController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified.student'])->group(function () {
    Route::prefix('courses/{course}/lessons/{lesson}/homework')
        ->name('homework.')
        ->group(function () {
            Route::post('/submit', SubmitHomeworkController::class)->name('submit');
            Route::get('/submissions/{submission}/files/{index}', DownloadFileController::class)->name('download');
        });
});
