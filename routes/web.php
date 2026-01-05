<?php

use App\Applications\Http\Public\Controllers\VerifyCertificateController;
use App\Applications\Http\Student\Dashboard\Controllers\ShowDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', ShowDashboardController::class)
    ->middleware(['auth', 'verified.student'])
    ->name('student.dashboard');

Route::get('verify/{certificateNumber}', VerifyCertificateController::class)
    ->name('certificate.verify');

Route::prefix('student')->name('student.')->group(function () {
    require __DIR__.'/student/auth.php';
    require __DIR__.'/student/registration.php';
    require __DIR__.'/student/profile.php';
    require __DIR__.'/student/courses.php';
    require __DIR__.'/student/catalog.php';
    require __DIR__.'/student/transactions.php';
    require __DIR__.'/student/payment.php';
    require __DIR__.'/student/certificates.php';
    require __DIR__.'/student/webinars.php';
    require __DIR__.'/student/homework.php';
});

Route::prefix('admin')->name('admin.')->group(function () {
    require __DIR__.'/admin/auth.php';
    require __DIR__.'/admin/dashboard.php';
    require __DIR__.'/admin/courses.php';
    require __DIR__.'/admin/modules.php';
    require __DIR__.'/admin/lessons.php';
    require __DIR__.'/admin/students.php';
    require __DIR__.'/admin/student-groups.php';
    require __DIR__.'/admin/progress.php';
    require __DIR__.'/admin/teachers.php';
    require __DIR__.'/admin/users.php';
    require __DIR__.'/admin/finances.php';
    require __DIR__.'/admin/certificates.php';
    require __DIR__.'/admin/webinars.php';
    require __DIR__.'/admin/quizzes.php';
    require __DIR__.'/admin/homework.php';
    require __DIR__.'/admin/comments.php';
});
