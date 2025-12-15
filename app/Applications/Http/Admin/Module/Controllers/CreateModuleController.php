<?php

namespace App\Applications\Http\Admin\Module\Controllers;

use App\Domains\Course\Models\Course;
use App\Domains\Module\Enums\ModuleStatus;
use App\Domains\Module\Enums\ModuleUnlockRule;
use App\Domains\Module\Models\Module;
use Illuminate\View\View;

final class CreateModuleController
{
    public function __invoke(Course $course): View
    {
        $this->authorize('create', [Module::class, $course]);

        $moduleStatuses = ModuleStatus::cases();
        $moduleUnlockRules = ModuleUnlockRule::cases();

        $breadcrumbs = [
            ['title' => 'Курси', 'url' => route('admin.courses.index')],
            ['title' => $course->name, 'url' => route('admin.courses.show', $course)],
            ['title' => 'Модулі', 'url' => route('admin.modules.index', $course)],
            ['title' => 'Створити'],
        ];

        return view('admin.modules.create', compact('course', 'moduleStatuses', 'moduleUnlockRules', 'breadcrumbs'));
    }

    private function authorize(string $ability, array $arguments): void
    {
        if (!app(\Illuminate\Contracts\Auth\Access\Gate::class)->allows($ability, $arguments)) {
            abort(403);
        }
    }
}
