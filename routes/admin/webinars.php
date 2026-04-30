<?php

use App\Applications\Http\Admin\Webinar\Controllers\CreateWebinarController;
use App\Applications\Http\Admin\Webinar\Controllers\DeleteWebinarController;
use App\Applications\Http\Admin\Webinar\Controllers\EditWebinarController;
use App\Applications\Http\Admin\Webinar\Controllers\GetWebinarsController;
use App\Applications\Http\Admin\Webinar\Controllers\ShowWebinarController;
use App\Applications\Http\Admin\Webinar\Controllers\StoreWebinarController;
use App\Applications\Http\Admin\Webinar\Controllers\UpdateWebinarController;
use App\Applications\Http\Admin\WebinarQuiz\Controllers\CreateWebinarQuizQuestionController;
use App\Applications\Http\Admin\WebinarQuiz\Controllers\DeleteWebinarQuizQuestionController;
use App\Applications\Http\Admin\WebinarQuiz\Controllers\EditWebinarQuizQuestionController;
use App\Applications\Http\Admin\WebinarQuiz\Controllers\GetWebinarQuizQuestionsController;
use App\Applications\Http\Admin\WebinarQuiz\Controllers\ReorderWebinarQuizQuestionsController;
use App\Applications\Http\Admin\WebinarQuiz\Controllers\StoreWebinarQuizQuestionController;
use App\Applications\Http\Admin\WebinarQuiz\Controllers\UpdateWebinarQuizQuestionController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:admin', 'verified.user', 'role:admin'])->group(function () {
    Route::prefix('webinars')->name('webinars.')->group(function () {
        Route::get('/', GetWebinarsController::class)->name('index');
        Route::get('/create', CreateWebinarController::class)->name('create');
        Route::post('/', StoreWebinarController::class)->name('store');
        Route::get('/{webinar}', ShowWebinarController::class)->name('show');
        Route::get('/{webinar}/edit', EditWebinarController::class)->name('edit');
        Route::patch('/{webinar}', UpdateWebinarController::class)->name('update');
        Route::delete('/{webinar}', DeleteWebinarController::class)->name('destroy');
    });

    Route::prefix('webinars/{webinar}/quiz')
        ->name('webinar-quiz.')
        ->group(function () {
            Route::get('/questions', GetWebinarQuizQuestionsController::class)->name('questions.index');
            Route::get('/questions/create', CreateWebinarQuizQuestionController::class)->name('questions.create');
            Route::post('/questions', StoreWebinarQuizQuestionController::class)->name('questions.store');
            Route::get('/questions/{question}/edit', EditWebinarQuizQuestionController::class)->name('questions.edit');
            Route::patch('/questions/{question}', UpdateWebinarQuizQuestionController::class)->name('questions.update');
            Route::delete('/questions/{question}', DeleteWebinarQuizQuestionController::class)->name('questions.destroy');
            Route::patch('/questions/reorder', ReorderWebinarQuizQuestionsController::class)->name('questions.reorder');
        });
});
