<?php

namespace App\Applications\Http\Admin\Course\Controllers;

use App\Domains\Course\Models\Course;
use App\Domains\Module\Enums\ModuleStatus;
use App\Domains\Module\Enums\ModuleUnlockRule;
use App\Domains\Teacher\Models\Teacher;
use Illuminate\View\View;

final class EditCourseController
{
    public function __invoke(Course $course): View
    {
        $course->load([
            'tags',
            'author',
            'teacher',
            'modules' => fn ($q) => $q->orderBy('order')->withCount('lessons'),
        ]);

        $teachers = Teacher::orderBy('last_name')->get();
        $moduleStatuses = ModuleStatus::cases();
        $moduleUnlockRules = ModuleUnlockRule::cases();

        return view('admin.courses.edit', compact(
            'course',
            'teachers',
            'moduleStatuses',
            'moduleUnlockRules'
        ));
    }
}
