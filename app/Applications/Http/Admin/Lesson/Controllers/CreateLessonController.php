<?php

namespace App\Applications\Http\Admin\Lesson\Controllers;

use App\Domains\Course\Models\Course;
use App\Domains\Lesson\Enums\LessonStatus;
use App\Domains\Lesson\Enums\LessonType;
use App\Domains\Lesson\Models\Lesson;
use App\Domains\Module\Models\Module;
use Illuminate\View\View;

final class CreateLessonController
{
    public function __invoke(Course $course, Module $module): View
    {
        $this->authorize('create', [Lesson::class, $module]);

        $lessonTypes = LessonType::cases();
        $lessonStatuses = LessonStatus::cases();

        return view('admin.lessons.create', compact('course', 'module', 'lessonTypes', 'lessonStatuses'));
    }

    private function authorize(string $ability, array $arguments): void
    {
        if (! app(\Illuminate\Contracts\Auth\Access\Gate::class)->allows($ability, $arguments)) {
            abort(403);
        }
    }
}
