<?php

namespace App\Applications\Http\Admin\Lesson\Controllers;

use App\Domains\Course\Models\Course;
use App\Domains\Lesson\Actions\CreateLessonAction;
use App\Domains\Lesson\Data\CreateLessonData;
use App\Domains\Lesson\Models\Lesson;
use App\Domains\Module\Models\Module;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class StoreLessonController
{
    public function __invoke(Request $request, Course $course, Module $module): JsonResponse|RedirectResponse
    {
        $this->authorize('create', [Lesson::class, $module]);

        $data = CreateLessonData::from($request->all());
        $lesson = CreateLessonAction::execute($module, $data);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'lesson' => $lesson,
            ]);
        }

        return redirect()
            ->route('admin.lessons.index', [$course, $module])
            ->with('success', 'Урок успішно створено');
    }

    private function authorize(string $ability, array $arguments): void
    {
        if (!app(\Illuminate\Contracts\Auth\Access\Gate::class)->allows($ability, $arguments)) {
            abort(403);
        }
    }
}
