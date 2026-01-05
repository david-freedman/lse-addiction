<?php

use App\Applications\Http\Admin\Lesson\Controllers\CreateLessonController;
use App\Applications\Http\Admin\Lesson\Controllers\DeleteLessonController;
use App\Applications\Http\Admin\Lesson\Controllers\EditLessonController;
use App\Applications\Http\Admin\Lesson\Controllers\GetLessonsController;
use App\Applications\Http\Admin\Lesson\Controllers\ReorderLessonsController;
use App\Applications\Http\Admin\Lesson\Controllers\StoreLessonController;
use App\Applications\Http\Admin\Lesson\Controllers\UpdateLessonController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:admin', 'verified.user', 'role:admin,teacher'])->group(function () {
    Route::prefix('courses/{course}/modules/{module}/lessons')->name('lessons.')->group(function () {
        Route::get('/', GetLessonsController::class)->name('index');
        Route::get('/create', CreateLessonController::class)->name('create');
        Route::post('/', StoreLessonController::class)->name('store');
        Route::patch('/reorder', ReorderLessonsController::class)->name('reorder');
        Route::get('/{lesson}/edit', EditLessonController::class)->name('edit');
        Route::patch('/{lesson}', UpdateLessonController::class)->name('update');
        Route::delete('/{lesson}', DeleteLessonController::class)->name('destroy');
    });
});
