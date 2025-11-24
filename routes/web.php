<?php

use App\Applications\Http\Admin\Controllers\CourseController;
use App\Applications\Http\Admin\Controllers\StudentController as AdminStudentController;
use App\Applications\Http\Student\Controllers\CourseCatalogController;
use App\Applications\Http\Student\Controllers\MyCoursesController;
use App\Applications\Http\Student\Controllers\PaymentController;
use App\Applications\Http\Student\Controllers\StudentAuthController;
use App\Applications\Http\Student\Controllers\StudentDashboardController;
use App\Applications\Http\Student\Controllers\StudentProfileController;
use App\Applications\Http\Student\Controllers\StudentRegistrationController;
use App\Applications\Http\Student\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::get('/', [StudentDashboardController::class, 'index'])
    ->middleware(['auth', 'verified.student'])
    ->name('home');

Route::prefix('student')->name('student.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('register', [StudentRegistrationController::class, 'showRegistrationForm'])->name('register');
        Route::post('register', [StudentRegistrationController::class, 'register']);

        Route::post('registration/send-code', [StudentRegistrationController::class, 'sendVerificationCode'])->name('registration.send-code');
        Route::post('registration/verify-code', [StudentRegistrationController::class, 'verifyVerificationCode'])->name('registration.verify-code');
        Route::post('registration/resend-code', [StudentRegistrationController::class, 'resendVerificationCodeJson'])->name('registration.resend-code');

        Route::get('verify-phone', [StudentRegistrationController::class, 'showVerifyPhone'])->name('verify-phone.show');
        Route::post('verify-phone', [StudentRegistrationController::class, 'verifyPhone'])->name('verify-phone');

        Route::get('verify-email', [StudentRegistrationController::class, 'showVerifyEmail'])->name('verify-email.show');
        Route::post('verify-email', [StudentRegistrationController::class, 'verifyEmail'])->name('verify-email');

        Route::get('contact-details', [StudentRegistrationController::class, 'showContactDetails'])->name('contact-details.show');
        Route::post('contact-details', [StudentRegistrationController::class, 'saveContactDetails'])->name('contact-details');

        Route::get('profile-fields', [StudentRegistrationController::class, 'showProfileFields'])->name('profile-fields.show');
        Route::post('profile-fields', [StudentRegistrationController::class, 'saveProfileFields'])->name('profile-fields.save');
        Route::post('profile-fields/skip', [StudentRegistrationController::class, 'skipProfileFields'])->name('profile-fields.skip');

        Route::post('resend-code', [StudentRegistrationController::class, 'resendCode'])->name('resend-code');

        Route::get('login', [StudentAuthController::class, 'showLoginForm'])->name('login');
        Route::post('login', [StudentAuthController::class, 'sendLoginCode'])->name('login.send');

        Route::get('verify-login', [StudentAuthController::class, 'showVerifyLogin'])->name('verify-login.show');
        Route::post('verify-login', [StudentAuthController::class, 'verifyLogin'])->name('verify-login');
        Route::post('verify-login/resend', [StudentAuthController::class, 'resendLoginCode'])->name('verify-login.resend');
    });

    Route::middleware('auth')->group(function () {
        Route::get('complete-verification', [StudentAuthController::class, 'showCompleteVerification'])->name('complete-verification');
        Route::post('complete-verification', [StudentAuthController::class, 'verifyCompleteVerification'])->name('complete-verification.verify');
        Route::post('complete-verification/resend', [StudentAuthController::class, 'resendVerificationCode'])->name('complete-verification.resend');
    });

    Route::middleware(['auth', 'verified.student'])->group(function () {
        Route::get('profile', [StudentProfileController::class, 'show'])->name('profile.show');
        Route::get('profile/edit', [StudentProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('profile', [StudentProfileController::class, 'update'])->name('profile.update');
        Route::patch('profile/profile-fields', [StudentProfileController::class, 'updateProfileFields'])->name('profile.profile-fields.update');

        Route::get('verify-change', [StudentProfileController::class, 'showVerifyChange'])->name('verify-change.show');
        Route::post('verify-change', [StudentProfileController::class, 'verifyChange'])->name('verify-change');
        Route::post('verify-change/resend', [StudentProfileController::class, 'resendChangeCode'])->name('verify-change.resend');

        Route::post('logout', [StudentAuthController::class, 'logout'])->name('logout');

        Route::get('my-courses', [MyCoursesController::class, 'index'])->name('my-courses');
        Route::get('courses/{course}', [MyCoursesController::class, 'show'])->name('courses.show');
        Route::post('courses/{course}/enroll', [MyCoursesController::class, 'enroll'])->name('courses.enroll');
        Route::post('courses/{course}/unenroll', [MyCoursesController::class, 'unenroll'])->name('courses.unenroll');

        Route::get('catalog', [CourseCatalogController::class, 'index'])->name('catalog.index');
        Route::get('catalog/{course}', [CourseCatalogController::class, 'show'])->name('catalog.show');
        Route::post('catalog/{course}/purchase', [CourseCatalogController::class, 'purchase'])->name('catalog.purchase');

        Route::get('transactions', [TransactionController::class, 'index'])->name('transactions.index');

        Route::get('payment/{transaction}/initiate', [PaymentController::class, 'initiate'])->name('payment.initiate');
        Route::post('payment/{transaction}/cancel', [PaymentController::class, 'cancel'])->name('payment.cancel');
    });

    Route::post('payment/callback', [PaymentController::class, 'callback'])->name('payment.callback');
    Route::any('payment/return', [PaymentController::class, 'return'])->name('payment.return');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [App\Applications\Http\Admin\Controllers\AdminAuthController::class, 'showLoginForm'])
            ->name('login');
        Route::post('/login', [App\Applications\Http\Admin\Controllers\AdminAuthController::class, 'sendLoginCode'])
            ->middleware('throttle:5,1')
            ->name('login.send');
        Route::get('/verify-login', [App\Applications\Http\Admin\Controllers\AdminAuthController::class, 'showVerifyLogin'])
            ->name('verify-login.show');
        Route::post('/verify-login', [App\Applications\Http\Admin\Controllers\AdminAuthController::class, 'verifyLogin'])
            ->middleware('throttle:5,1')
            ->name('verify-login');
        Route::post('/verify-login/resend', [App\Applications\Http\Admin\Controllers\AdminAuthController::class, 'resendCode'])
            ->middleware('throttle:3,1')
            ->name('verify-login.resend');
    });

    Route::middleware(['auth:admin', 'verified.user'])->group(function () {
        Route::post('/logout', [App\Applications\Http\Admin\Controllers\AdminAuthController::class, 'logout'])
            ->name('logout');

        Route::get('/', [App\Applications\Http\Admin\Controllers\DashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('/ui-kit', [App\Applications\Http\Admin\Controllers\UIKitController::class, 'index'])
            ->name('ui-kit');

        Route::middleware('role:admin')->group(function () {
            Route::resource('courses', CourseController::class);

            Route::resource('students', AdminStudentController::class);
            Route::post('students/{studentId}/restore', [AdminStudentController::class, 'restore'])
                ->name('students.restore');
            Route::post('students/{student}/assign-to-course', [AdminStudentController::class, 'assignToCourse'])
                ->name('students.assign-to-course');
            Route::delete('students/{student}/courses/{course}', [AdminStudentController::class, 'unenrollFromCourse'])
                ->name('students.unenroll-from-course');
            Route::post('students/bulk-assign', [AdminStudentController::class, 'bulkAssign'])
                ->name('students.bulk-assign');
            Route::post('students/bulk-delete', [AdminStudentController::class, 'bulkDelete'])
                ->name('students.bulk-delete');
        });
    });
});
