<?php

use App\Applications\Http\Student\Dashboard\Controllers\ShowDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', ShowDashboardController::class)
    ->middleware(['auth', 'verified.student'])
    ->name('home');

Route::prefix('student')->name('student.')->group(function () {
    require __DIR__.'/student/auth.php';
    require __DIR__.'/student/registration.php';
    require __DIR__.'/student/profile.php';
    require __DIR__.'/student/courses.php';
    require __DIR__.'/student/catalog.php';
    require __DIR__.'/student/transactions.php';
    require __DIR__.'/student/payment.php';
});

Route::prefix('admin')->name('admin.')->group(function () {
    require __DIR__.'/admin/auth.php';
    require __DIR__.'/admin/dashboard.php';
    require __DIR__.'/admin/courses.php';
    require __DIR__.'/admin/students.php';
    require __DIR__.'/admin/teachers.php';
    require __DIR__.'/admin/users.php';
    require __DIR__.'/admin/finances.php';
});
