<?php

use App\Applications\Http\Student\Course\Controllers\EnrollCourseController;
use App\Applications\Http\Student\Course\Controllers\GetMyCoursesController;
use App\Applications\Http\Student\Course\Controllers\ShowCourseController;
use App\Applications\Http\Student\Course\Controllers\UnenrollCourseController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified.student'])->group(function () {
    Route::get('my-courses', GetMyCoursesController::class)->name('my-courses');
    Route::get('courses/{course}', ShowCourseController::class)->name('courses.show');
    Route::post('courses/{course}/enroll', EnrollCourseController::class)->name('courses.enroll');
    Route::post('courses/{course}/unenroll', UnenrollCourseController::class)->name('courses.unenroll');
});
