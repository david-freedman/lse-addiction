<?php

use App\Applications\Http\Admin\Comment\Controllers\DeleteCommentController;
use App\Applications\Http\Admin\Comment\Controllers\GetCommentsController;
use App\Applications\Http\Admin\Comment\Controllers\GetLessonsJsonController;
use App\Applications\Http\Admin\Comment\Controllers\StoreReplyController;
use App\Domains\Lesson\Models\LessonComment;
use Illuminate\Support\Facades\Route;

Route::bind('comment', fn (string $value) => LessonComment::findOrFail($value));

Route::middleware(['auth:admin', 'verified.user', 'role:admin,teacher'])->group(function () {
    Route::prefix('comments')->name('comments.')->group(function () {
        Route::get('/', GetCommentsController::class)->name('index');
        Route::get('/lessons/{courseId}', GetLessonsJsonController::class)->name('lessons');
        Route::post('/{comment}/reply', StoreReplyController::class)->name('reply');
        Route::delete('/{comment}', DeleteCommentController::class)->name('destroy');
    });
});
