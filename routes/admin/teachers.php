<?php

use App\Applications\Http\Admin\Teacher\Controllers\CreateTeacherController;
use App\Applications\Http\Admin\Teacher\Controllers\DeleteTeacherController;
use App\Applications\Http\Admin\Teacher\Controllers\EditTeacherController;
use App\Applications\Http\Admin\Teacher\Controllers\GetTeachersController;
use App\Applications\Http\Admin\Teacher\Controllers\ShowTeacherController;
use App\Applications\Http\Admin\Teacher\Controllers\StoreTeacherController;
use App\Applications\Http\Admin\Teacher\Controllers\UpdateTeacherController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:admin', 'verified.user', 'role:admin'])->group(function () {
    Route::prefix('teachers')->name('teachers.')->group(function () {
        Route::get('/', GetTeachersController::class)->name('index');
        Route::get('/create', CreateTeacherController::class)->name('create');
        Route::post('/', StoreTeacherController::class)->name('store');
        Route::get('/{teacher}', ShowTeacherController::class)->name('show');
        Route::get('/{teacher}/edit', EditTeacherController::class)->name('edit');
        Route::patch('/{teacher}', UpdateTeacherController::class)->name('update');
        Route::delete('/{teacher}', DeleteTeacherController::class)->name('delete');
    });
});
