<?php

use App\Applications\Http\Admin\Quiz\Controllers\CreateQuestionController;
use App\Applications\Http\Admin\Quiz\Controllers\DeleteQuestionController;
use App\Applications\Http\Admin\Quiz\Controllers\EditQuestionController;
use App\Applications\Http\Admin\Quiz\Controllers\GetQuizQuestionsController;
use App\Applications\Http\Admin\Quiz\Controllers\GetQuizResultsController;
use App\Applications\Http\Admin\Quiz\Controllers\ReorderQuestionsController;
use App\Applications\Http\Admin\Quiz\Controllers\ShowQuizAttemptController;
use App\Applications\Http\Admin\Quiz\Controllers\StoreQuestionController;
use App\Applications\Http\Admin\Quiz\Controllers\UpdateQuestionController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:admin', 'verified.user', 'role:admin,teacher'])->group(function () {
    Route::prefix('courses/{course}/modules/{module}/lessons/{lesson}/quiz')
        ->name('quiz.')
        ->group(function () {
            Route::get('/questions', GetQuizQuestionsController::class)->name('questions.index');
            Route::get('/questions/create', CreateQuestionController::class)->name('questions.create');
            Route::post('/questions', StoreQuestionController::class)->name('questions.store');
            Route::get('/questions/{question}/edit', EditQuestionController::class)->name('questions.edit');
            Route::patch('/questions/{question}', UpdateQuestionController::class)->name('questions.update');
            Route::delete('/questions/{question}', DeleteQuestionController::class)->name('questions.destroy');
            Route::patch('/questions/reorder', ReorderQuestionsController::class)->name('questions.reorder');
        });

    Route::get('quizzes/{quiz}/results', GetQuizResultsController::class)->name('quizzes.results');
    Route::get('quiz-attempts/{attempt}', ShowQuizAttemptController::class)->name('quiz-attempts.show');
});
