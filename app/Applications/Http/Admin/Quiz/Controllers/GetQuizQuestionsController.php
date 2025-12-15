<?php

namespace App\Applications\Http\Admin\Quiz\Controllers;

use App\Domains\Course\Models\Course;
use App\Domains\Lesson\Models\Lesson;
use App\Domains\Module\Models\Module;
use App\Domains\Quiz\ViewModels\Admin\QuizQuestionsViewModel;
use Illuminate\View\View;

final class GetQuizQuestionsController
{
    public function __invoke(Course $course, Module $module, Lesson $lesson): View
    {
        $lesson->load('quiz.questions.answers');

        $viewModel = new QuizQuestionsViewModel($course, $module, $lesson);

        $breadcrumbs = [
            ['title' => 'Курси', 'url' => route('admin.courses.index')],
            ['title' => $course->name, 'url' => route('admin.courses.show', $course)],
            ['title' => $module->name, 'url' => route('admin.modules.edit', [$course, $module])],
            ['title' => $lesson->name, 'url' => route('admin.lessons.edit', [$course, $module, $lesson])],
            ['title' => 'Питання'],
        ];

        return view('admin.quiz.questions.index', compact('viewModel', 'breadcrumbs'));
    }
}
