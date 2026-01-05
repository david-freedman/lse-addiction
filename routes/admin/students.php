<?php

use App\Applications\Http\Admin\Student\Controllers\AssignDiscountController;
use App\Applications\Http\Admin\Student\Controllers\AssignStudentToCourseController;
use App\Applications\Http\Admin\Student\Controllers\AssignStudentToWebinarController;
use App\Applications\Http\Admin\Student\Controllers\BulkAssignStudentsController;
use App\Applications\Http\Admin\Student\Controllers\BulkDeleteStudentsController;
use App\Applications\Http\Admin\Student\Controllers\CreateStudentController;
use App\Applications\Http\Admin\Student\Controllers\DeleteStudentController;
use App\Applications\Http\Admin\Student\Controllers\EditStudentController;
use App\Applications\Http\Admin\Student\Controllers\GetStudentsController;
use App\Applications\Http\Admin\Student\Controllers\RemoveDiscountController;
use App\Applications\Http\Admin\Student\Controllers\RestoreStudentController;
use App\Applications\Http\Admin\Student\Controllers\ShowStudentController;
use App\Applications\Http\Admin\Student\Controllers\StoreStudentController;
use App\Applications\Http\Admin\Student\Controllers\UnenrollStudentFromCourseController;
use App\Applications\Http\Admin\Student\Controllers\UnregisterStudentFromWebinarController;
use App\Applications\Http\Admin\Student\Controllers\UpdateStudentController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:admin', 'verified.user', 'role:admin,teacher'])->group(function () {
    Route::prefix('students')->name('students.')->group(function () {
        Route::get('/', GetStudentsController::class)->name('index');
        Route::get('/{student}', ShowStudentController::class)->name('show')->can('view', 'student');
        Route::get('/{student}/edit', EditStudentController::class)->name('edit')->can('update', 'student');
        Route::patch('/{student}', UpdateStudentController::class)->name('update')->can('update', 'student');

        Route::middleware('role:admin')->group(function () {
            Route::get('/create', CreateStudentController::class)->name('create');
            Route::post('/', StoreStudentController::class)->name('store');
            Route::delete('/{student}', DeleteStudentController::class)->name('destroy');
            Route::post('/{studentId}/restore', RestoreStudentController::class)->name('restore');
            Route::post('/{student}/assign-to-course', AssignStudentToCourseController::class)->name('assign-to-course');
            Route::delete('/{student}/courses/{course}', UnenrollStudentFromCourseController::class)->name('unenroll-from-course');
            Route::post('/{student}/assign-to-webinar', AssignStudentToWebinarController::class)->name('assign-to-webinar');
            Route::delete('/{student}/webinars/{webinar}', UnregisterStudentFromWebinarController::class)->name('unregister-from-webinar');
            Route::post('/{student}/assign-discount', AssignDiscountController::class)->name('assign-discount');
            Route::delete('/{student}/discounts/{discount}', RemoveDiscountController::class)->name('remove-discount');
            Route::post('/bulk-assign', BulkAssignStudentsController::class)->name('bulk-assign');
            Route::post('/bulk-delete', BulkDeleteStudentsController::class)->name('bulk-delete');
        });
    });
});
