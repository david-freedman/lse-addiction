<?php

namespace App\Applications\Http\Admin\Module\Controllers;

use App\Domains\Course\Models\Course;
use App\Domains\Module\Actions\ReorderModulesAction;
use App\Domains\Module\Models\Module;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class ReorderModulesController
{
    public function __invoke(Request $request, Course $course): JsonResponse
    {
        $this->authorize('reorder', [Module::class, $course]);

        $request->validate([
            'order' => ['required', 'array'],
            'order.*' => ['required', 'integer', 'exists:modules,id'],
        ]);

        ReorderModulesAction::execute($course, $request->input('order'));

        return response()->json(['success' => true]);
    }

    private function authorize(string $ability, array $arguments): void
    {
        if (! app(\Illuminate\Contracts\Auth\Access\Gate::class)->allows($ability, $arguments)) {
            abort(403);
        }
    }
}
