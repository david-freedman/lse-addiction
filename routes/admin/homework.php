<?php

use App\Applications\Http\Admin\Homework\Controllers\DownloadSubmissionFileController;
use App\Applications\Http\Admin\Homework\Controllers\ExportHomeworkResultsController;
use App\Applications\Http\Admin\Homework\Controllers\GetLessonsJsonController;
use App\Applications\Http\Admin\Homework\Controllers\GetModulesJsonController;
use App\Applications\Http\Admin\Homework\Controllers\GetSubmissionsInboxController;
use App\Applications\Http\Admin\Homework\Controllers\ReviewSubmissionController;
use App\Applications\Http\Admin\Homework\Controllers\ShowSubmissionController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:admin', 'verified.user', 'role:admin,teacher'])->group(function () {
    Route::prefix('homework')->name('homework.')->group(function () {
        Route::get('/', GetSubmissionsInboxController::class)->name('index');
        Route::get('/export', ExportHomeworkResultsController::class)->name('export');
        Route::get('/modules/{courseId}', GetModulesJsonController::class)->name('modules');
        Route::get('/lessons/{module}', GetLessonsJsonController::class)->name('lessons');

        Route::get('/submissions/{submission}', ShowSubmissionController::class)->name('submissions.show');
        Route::patch('/submissions/{submission}/review', ReviewSubmissionController::class)->name('submissions.review');
        Route::get('/submissions/{submission}/files/{index}', DownloadSubmissionFileController::class)->name('submissions.download');
    });
});
