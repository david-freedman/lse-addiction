<?php

namespace App\Applications\Http\Admin\Quiz\Controllers;

use App\Domains\Course\Models\Course;
use App\Domains\Lesson\Models\Lesson;
use App\Domains\Module\Models\Module;
use App\Domains\Quiz\Enums\QuestionType;
use Illuminate\View\View;

final class CreateQuestionController
{
    public function __invoke(Course $course, Module $module, Lesson $lesson): View
    {
        $questionTypes = QuestionType::cases();

        $breadcrumbs = [
            ['title' => 'Курси', 'url' => route('admin.courses.index')],
            ['title' => $course->name, 'url' => route('admin.courses.show', $course)],
            ['title' => $module->name, 'url' => route('admin.modules.edit', [$course, $module])],
            ['title' => $lesson->name, 'url' => route('admin.lessons.edit', [$course, $module, $lesson])],
            ['title' => 'Питання', 'url' => route('admin.quiz.questions.index', [$course, $module, $lesson])],
            ['title' => 'Створити'],
        ];

        return view('admin.quiz.questions.create', compact('course', 'module', 'lesson', 'questionTypes', 'breadcrumbs'));
    }
}
