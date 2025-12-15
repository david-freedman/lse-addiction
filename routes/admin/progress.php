<?php

use App\Applications\Http\Admin\Progress\Controllers\GetStudentsByCourseController;
use App\Applications\Http\Admin\Progress\Controllers\ShowCourseProgressController;
use App\Applications\Http\Admin\Progress\Controllers\ShowProgressDashboardController;
use App\Applications\Http\Admin\Progress\Controllers\ShowStudentProgressController;
use App\Applications\Http\Admin\Progress\Controllers\ShowStudentProgressTreeController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:admin', 'verified.user', 'role:admin'])->group(function () {
    Route::prefix('progress')->name('progress.')->group(function () {
        Route::get('/', ShowProgressDashboardController::class)->name('dashboard')
            ->can('access-progress');
        Route::get('/tree', ShowStudentProgressTreeController::class)->name('tree')
            ->can('access-progress');
        Route::get('/api/students/{course:id}', GetStudentsByCourseController::class)->name('api.students-by-course')
            ->can('access-progress');
        Route::get('/courses/{course}', ShowCourseProgressController::class)->name('course')
            ->can('access-progress');
        Route::get('/student/{student}', ShowStudentProgressController::class)->name('student')
            ->can('access-progress');
    });
});
