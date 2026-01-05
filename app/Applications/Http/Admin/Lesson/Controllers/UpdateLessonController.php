<?php

namespace App\Applications\Http\Admin\Lesson\Controllers;

use App\Domains\Course\Models\Course;
use App\Domains\Lesson\Actions\UpdateLessonAction;
use App\Domains\Lesson\Data\UpdateLessonData;
use App\Domains\Lesson\Models\Lesson;
use App\Domains\Module\Models\Module;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class UpdateLessonController
{
    public function __invoke(Request $request, Course $course, Module $module, Lesson $lesson): JsonResponse|RedirectResponse
    {
        $this->authorize('update', $lesson);

        $data = UpdateLessonData::from($request->all());
        $updatedLesson = UpdateLessonAction::execute($lesson, $data);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'lesson' => $updatedLesson,
            ]);
        }

        return redirect()
            ->route('admin.lessons.index', [$course, $module])
            ->with('success', 'Урок успішно оновлено');
    }

    private function authorize(string $ability, $model): void
    {
        if (!app(\Illuminate\Contracts\Auth\Access\Gate::class)->allows($ability, $model)) {
            abort(403);
        }
    }
}
