<?php

use App\Applications\Http\Admin\Course\Controllers\ArchiveCourseController;
use App\Applications\Http\Admin\Course\Controllers\CreateCourseController;
use App\Applications\Http\Admin\Course\Controllers\DeleteCourseController;
use App\Applications\Http\Admin\Course\Controllers\EditCourseController;
use App\Applications\Http\Admin\Course\Controllers\GetCoursesController;
use App\Applications\Http\Admin\Course\Controllers\RestoreCourseController;
use App\Applications\Http\Admin\Course\Controllers\ShowCourseController;
use App\Applications\Http\Admin\Course\Controllers\StoreCourseController;
use App\Applications\Http\Admin\Course\Controllers\UpdateCourseController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:admin', 'verified.user', 'role:admin,teacher'])->group(function () {
    Route::prefix('courses')->name('courses.')->group(function () {
        Route::get('/', GetCoursesController::class)->name('index');
        Route::get('/create', CreateCourseController::class)->name('create');
        Route::post('/', StoreCourseController::class)->name('store');
        Route::get('/{course}', ShowCourseController::class)->name('show')->can('view', 'course');
        Route::get('/{course}/edit', EditCourseController::class)->name('edit')->can('update', 'course');
        Route::patch('/{course}', UpdateCourseController::class)->name('update')->can('update', 'course');
        Route::patch('/{course}/archive', ArchiveCourseController::class)->name('archive')->can('archive', 'course');
        Route::patch('/{course}/restore', RestoreCourseController::class)->name('restore')->can('restore', 'course');
        Route::delete('/{course}', DeleteCourseController::class)->name('destroy')->can('delete', 'course');
    });
});
