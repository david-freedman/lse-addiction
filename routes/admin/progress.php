<?php

use App\Applications\Http\Admin\Progress\Controllers\ShowCourseProgressController;
use App\Applications\Http\Admin\Progress\Controllers\ShowProgressDashboardController;
use App\Applications\Http\Admin\Progress\Controllers\ShowStudentProgressController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:admin', 'verified.user', 'role:admin'])->group(function () {
    Route::prefix('progress')->name('progress.')->group(function () {
        Route::get('/', ShowProgressDashboardController::class)->name('dashboard')
            ->can('access-progress');
        Route::get('/courses/{course}', ShowCourseProgressController::class)->name('course')
            ->can('access-progress');
        Route::get('/students/{student}', ShowStudentProgressController::class)->name('student')
            ->can('access-progress');
    });
});
