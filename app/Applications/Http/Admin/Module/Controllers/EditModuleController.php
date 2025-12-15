<?php

namespace App\Applications\Http\Admin\Module\Controllers;

use App\Domains\Course\Models\Course;
use App\Domains\Module\Enums\ModuleStatus;
use App\Domains\Module\Enums\ModuleUnlockRule;
use App\Domains\Module\Models\Module;
use Illuminate\View\View;

final class EditModuleController
{
    public function __invoke(Course $course, Module $module): View
    {
        $this->authorize('update', $module);

        $module->loadCount('lessons');

        $moduleStatuses = ModuleStatus::cases();
        $moduleUnlockRules = ModuleUnlockRule::cases();

        $breadcrumbs = [
            ['title' => 'Курси', 'url' => route('admin.courses.index')],
            ['title' => $course->name, 'url' => route('admin.courses.show', $course)],
            ['title' => 'Модулі', 'url' => route('admin.modules.index', $course)],
            ['title' => $module->name],
        ];

        return view('admin.modules.edit', compact('course', 'module', 'moduleStatuses', 'moduleUnlockRules', 'breadcrumbs'));
    }

    private function authorize(string $ability, $model): void
    {
        if (!app(\Illuminate\Contracts\Auth\Access\Gate::class)->allows($ability, $model)) {
            abort(403);
        }
    }
}
