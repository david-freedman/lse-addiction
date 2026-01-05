<?php

namespace App\Applications\Http\Admin\Lesson\Controllers;

use App\Domains\Course\Models\Course;
use App\Domains\Lesson\Enums\LessonStatus;
use App\Domains\Lesson\Enums\LessonType;
use App\Domains\Lesson\Models\Lesson;
use App\Domains\Module\Models\Module;
use Illuminate\View\View;

final class EditLessonController
{
    public function __invoke(Course $course, Module $module, Lesson $lesson): View
    {
        $this->authorize('update', $lesson);

        $lesson->load(['quiz', 'homework']);

        $lessonTypes = LessonType::cases();
        $lessonStatuses = LessonStatus::cases();

        $breadcrumbs = [
            ['title' => 'Курси', 'url' => route('admin.courses.index')],
            ['title' => $course->name, 'url' => route('admin.courses.show', $course)],
            ['title' => $module->name, 'url' => route('admin.modules.edit', [$course, $module])],
            ['title' => $lesson->name],
        ];

        return view('admin.lessons.edit', compact('course', 'module', 'lesson', 'lessonTypes', 'lessonStatuses', 'breadcrumbs'));
    }

    private function authorize(string $ability, $model): void
    {
        if (! app(\Illuminate\Contracts\Auth\Access\Gate::class)->allows($ability, $model)) {
            abort(403);
        }
    }
}
