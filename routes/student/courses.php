<?php

use App\Applications\Http\Student\Course\Controllers\EnrollCourseController;
use App\Applications\Http\Student\Course\Controllers\GetMyCoursesController;
use App\Applications\Http\Student\Course\Controllers\ShowCourseController;
use App\Applications\Http\Student\Course\Controllers\UnenrollCourseController;
use App\Applications\Http\Student\Lesson\Controllers\CompleteLessonController;
use App\Applications\Http\Student\Lesson\Controllers\SaveLessonNoteController;
use App\Applications\Http\Student\Lesson\Controllers\ShowLessonController;
use App\Applications\Http\Student\Lesson\Controllers\StoreCommentController;
use App\Applications\Http\Student\Lesson\Controllers\StreamDicomController;
use App\Applications\Http\Student\Module\Controllers\ShowModuleController;
use App\Applications\Http\Student\Quiz\Controllers\ShowQuizController;
use App\Applications\Http\Student\Quiz\Controllers\SubmitQuizController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified.student'])->group(function () {
    Route::get('my-courses', GetMyCoursesController::class)->name('my-courses');
    Route::get('courses/{course}', ShowCourseController::class)->name('courses.show');
    Route::post('courses/{course}/enroll', EnrollCourseController::class)->name('courses.enroll');
    Route::post('courses/{course}/unenroll', UnenrollCourseController::class)->name('courses.unenroll');

    Route::get('courses/{course}/modules/{module}', ShowModuleController::class)->name('modules.show');

    Route::get('courses/{course}/lessons/{lesson}', ShowLessonController::class)->name('lessons.show');
    Route::post('courses/{course}/lessons/{lesson}/complete', CompleteLessonController::class)->name('lessons.complete');
    Route::post('courses/{course}/lessons/{lesson}/notes', SaveLessonNoteController::class)->name('lessons.notes.save');
    Route::post('courses/{course}/lessons/{lesson}/comments', StoreCommentController::class)->name('lessons.comments.store');
    Route::get('courses/{course}/lessons/{lesson}/dicom', StreamDicomController::class)->name('lessons.dicom');

    Route::get('courses/{course}/lessons/{lesson}/quiz', ShowQuizController::class)->name('quiz.show');
    Route::post('courses/{course}/lessons/{lesson}/quiz', SubmitQuizController::class)->name('quiz.submit');
});
