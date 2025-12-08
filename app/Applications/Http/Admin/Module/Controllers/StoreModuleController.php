<?php

namespace App\Applications\Http\Admin\Module\Controllers;

use App\Domains\Course\Models\Course;
use App\Domains\Module\Actions\CreateModuleAction;
use App\Domains\Module\Data\CreateModuleData;
use App\Domains\Module\Models\Module;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class StoreModuleController
{
    public function __invoke(Request $request, Course $course): JsonResponse|RedirectResponse
    {
        $this->authorize('create', [Module::class, $course]);

        $data = CreateModuleData::from($request->all());
        $module = CreateModuleAction::execute($course, $data);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'module' => $module,
            ]);
        }

        return redirect()
            ->route('admin.courses.edit', $course)
            ->with('success', 'Модуль успішно створено');
    }

    private function authorize(string $ability, array $arguments): void
    {
        if (!app(\Illuminate\Contracts\Auth\Access\Gate::class)->allows($ability, $arguments)) {
            abort(403);
        }
    }
}
