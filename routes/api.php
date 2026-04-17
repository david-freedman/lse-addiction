<?php

use App\Applications\Http\Api\CourseRegistration\Controllers\CourseRegistrationController;
use Illuminate\Support\Facades\Route;

Route::middleware('wp.sync.secret')->group(function () {
    Route::post('course-registrations', CourseRegistrationController::class)
        ->name('api.course-registrations');
});
