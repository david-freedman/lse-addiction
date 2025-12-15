<?php

namespace App\Applications\Http\Admin\Lesson\Controllers;

use App\Domains\Course\Models\Course;
use App\Domains\Lesson\ViewModels\ModuleLessonsViewModel;
use App\Domains\Module\Models\Module;
use Illuminate\View\View;

final class GetLessonsController
{
    public function __invoke(Course $course, Module $module): View
    {
        $this->authorize('viewAny', [\App\Domains\Lesson\Models\Lesson::class, $module]);

        $viewModel = new ModuleLessonsViewModel($course, $module);

        $breadcrumbs = [
            ['title' => 'Курси', 'url' => route('admin.courses.index')],
            ['title' => $course->name, 'url' => route('admin.courses.show', $course)],
            ['title' => $module->name, 'url' => route('admin.modules.edit', [$course, $module])],
            ['title' => 'Уроки'],
        ];

        return view('admin.lessons.index', compact('viewModel', 'course', 'module', 'breadcrumbs'));
    }

    private function authorize(string $ability, array $arguments): void
    {
        if (! app(\Illuminate\Contracts\Auth\Access\Gate::class)->allows($ability, $arguments)) {
            abort(403);
        }
    }
}
