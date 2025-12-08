<?php

namespace App\Applications\Http\Admin\Lesson\Controllers;

use App\Domains\Course\Models\Course;
use App\Domains\Lesson\Actions\ReorderLessonsAction;
use App\Domains\Lesson\Models\Lesson;
use App\Domains\Module\Models\Module;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class ReorderLessonsController
{
    public function __invoke(Request $request, Course $course, Module $module): JsonResponse
    {
        $this->authorize('reorder', [Lesson::class, $module]);

        $request->validate([
            'order' => ['required', 'array'],
            'order.*' => ['required', 'integer', 'exists:lessons,id'],
        ]);

        ReorderLessonsAction::execute($module, $request->input('order'));

        return response()->json(['success' => true]);
    }

    private function authorize(string $ability, array $arguments): void
    {
        if (! app(\Illuminate\Contracts\Auth\Access\Gate::class)->allows($ability, $arguments)) {
            abort(403);
        }
    }
}
