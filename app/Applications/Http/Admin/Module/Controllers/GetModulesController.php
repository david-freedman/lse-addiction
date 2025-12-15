<?php

namespace App\Applications\Http\Admin\Module\Controllers;

use App\Domains\Course\Models\Course;
use App\Domains\Module\Models\Module;
use Illuminate\View\View;

final class GetModulesController
{
    public function __invoke(Course $course): View
    {
        $this->authorize('viewAny', [Module::class, $course]);

        $modules = $course->modules()->withCount('lessons')->orderBy('order')->get();

        $breadcrumbs = [
            ['title' => 'Курси', 'url' => route('admin.courses.index')],
            ['title' => $course->name, 'url' => route('admin.courses.show', $course)],
            ['title' => 'Модулі'],
        ];

        return view('admin.modules.index', compact('course', 'modules', 'breadcrumbs'));
    }

    private function authorize(string $ability, array $arguments): void
    {
        if (!app(\Illuminate\Contracts\Auth\Access\Gate::class)->allows($ability, $arguments)) {
            abort(403);
        }
    }
}
