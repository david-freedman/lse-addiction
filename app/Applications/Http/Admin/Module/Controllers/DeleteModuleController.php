<?php

namespace App\Applications\Http\Admin\Module\Controllers;

use App\Domains\Course\Models\Course;
use App\Domains\Module\Actions\DeleteModuleAction;
use App\Domains\Module\Models\Module;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class DeleteModuleController
{
    public function __invoke(Request $request, Course $course, Module $module): JsonResponse|RedirectResponse
    {
        $this->authorize('delete', $module);

        DeleteModuleAction::execute($module);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
            ]);
        }

        return redirect()
            ->route('admin.courses.edit', $course)
            ->with('success', 'Модуль успішно видалено');
    }

    private function authorize(string $ability, $model): void
    {
        if (!app(\Illuminate\Contracts\Auth\Access\Gate::class)->allows($ability, $model)) {
            abort(403);
        }
    }
}
