<?php

namespace App\Applications\Http\Admin\Module\Controllers;

use App\Domains\Course\Models\Course;
use App\Domains\Module\Actions\UpdateModuleAction;
use App\Domains\Module\Data\UpdateModuleData;
use App\Domains\Module\Models\Module;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class UpdateModuleController
{
    public function __invoke(Request $request, Course $course, Module $module): JsonResponse|RedirectResponse
    {
        $this->authorize('update', $module);

        $data = UpdateModuleData::from($request->all());
        $updatedModule = UpdateModuleAction::execute($module, $data);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'module' => $updatedModule,
            ]);
        }

        return redirect()
            ->route('admin.courses.edit', $course)
            ->with('success', 'Модуль успішно оновлено');
    }

    private function authorize(string $ability, $model): void
    {
        if (!app(\Illuminate\Contracts\Auth\Access\Gate::class)->allows($ability, $model)) {
            abort(403);
        }
    }
}
