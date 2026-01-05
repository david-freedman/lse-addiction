<?php

namespace App\Applications\Http\Admin\Quiz\Controllers;

use App\Domains\Progress\Models\StudentQuizAttempt;
use App\Domains\Quiz\ViewModels\Admin\QuizAttemptDetailViewModel;
use Illuminate\View\View;

final class ShowQuizAttemptController
{
    public function __invoke(StudentQuizAttempt $attempt): View
    {
        $attempt->load(['student', 'quiz.questions.answers']);

        $viewModel = new QuizAttemptDetailViewModel($attempt);

        $quiz = $attempt->quiz;
        $lesson = $quiz->quizzable;
        $module = $lesson->module;
        $course = $module->course;

        $breadcrumbs = [
            ['title' => 'Курси', 'url' => route('admin.courses.index')],
            ['title' => $course->name, 'url' => route('admin.courses.show', $course)],
            ['title' => $module->name, 'url' => route('admin.modules.edit', [$course, $module])],
            ['title' => $lesson->name, 'url' => route('admin.lessons.edit', [$course, $module, $lesson])],
            ['title' => 'Результати', 'url' => route('admin.quizzes.results', $quiz)],
            ['title' => 'Спроба #' . $attempt->id],
        ];

        return view('admin.quiz.results.show', compact('viewModel', 'breadcrumbs'));
    }
}
