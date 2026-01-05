<?php

use App\Applications\Http\Admin\StudentGroup\Controllers\AddStudentsToGroupController;
use App\Applications\Http\Admin\StudentGroup\Controllers\CreateStudentGroupController;
use App\Applications\Http\Admin\StudentGroup\Controllers\DeleteStudentGroupController;
use App\Applications\Http\Admin\StudentGroup\Controllers\EditStudentGroupController;
use App\Applications\Http\Admin\StudentGroup\Controllers\GetStudentGroupsController;
use App\Applications\Http\Admin\StudentGroup\Controllers\RemoveStudentFromGroupController;
use App\Applications\Http\Admin\StudentGroup\Controllers\ShowStudentGroupController;
use App\Applications\Http\Admin\StudentGroup\Controllers\StoreStudentGroupController;
use App\Applications\Http\Admin\StudentGroup\Controllers\UpdateStudentGroupController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:admin', 'verified.user', 'role:admin,teacher'])->group(function () {
    Route::prefix('student-groups')->name('student-groups.')->group(function () {
        Route::get('/', GetStudentGroupsController::class)->name('index')
            ->can('access-student-groups');
        Route::get('/create', CreateStudentGroupController::class)->name('create')
            ->can('access-student-groups');
        Route::post('/', StoreStudentGroupController::class)->name('store')
            ->can('access-student-groups');
        Route::get('/{studentGroup}', ShowStudentGroupController::class)->name('show')
            ->can('access-student-groups');
        Route::get('/{studentGroup}/edit', EditStudentGroupController::class)->name('edit')
            ->can('access-student-groups');
        Route::patch('/{studentGroup}', UpdateStudentGroupController::class)->name('update')
            ->can('access-student-groups');
        Route::delete('/{studentGroup}', DeleteStudentGroupController::class)->name('destroy')
            ->can('access-student-groups');
        Route::post('/{studentGroup}/students', AddStudentsToGroupController::class)->name('add-students')
            ->can('access-student-groups');
        Route::delete('/{studentGroup}/students/{student}', RemoveStudentFromGroupController::class)->name('remove-student')
            ->can('access-student-groups');
    });
});
