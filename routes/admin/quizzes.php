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
use App\Applications\Http\Admin\QuizResults\Controllers\ExportQuizResultsController;
use App\Applications\Http\Admin\QuizResults\Controllers\GetLessonsWithQuizzesJsonController;
use App\Applications\Http\Admin\QuizResults\Controllers\GetModulesWithQuizzesJsonController;
use App\Applications\Http\Admin\QuizResults\Controllers\GetQuizResultsIndexController;
use App\Applications\Http\Admin\QuizResults\Controllers\GetQuizzesForLessonJsonController;
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

    Route::prefix('quiz-results')->name('quiz-results.')->group(function () {
        Route::get('/', GetQuizResultsIndexController::class)->name('index');
        Route::get('/export', ExportQuizResultsController::class)->name('export');
        Route::get('/modules/{courseId}', GetModulesWithQuizzesJsonController::class)->name('modules');
        Route::get('/lessons/{moduleId}', GetLessonsWithQuizzesJsonController::class)->name('lessons');
        Route::get('/quizzes/{lessonId}', GetQuizzesForLessonJsonController::class)->name('quizzes');
    });
});
