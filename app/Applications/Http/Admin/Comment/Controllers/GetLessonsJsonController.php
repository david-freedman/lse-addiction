<?php

namespace App\Applications\Http\Admin\Comment\Controllers;

use App\Domains\Lesson\Models\Lesson;
use Illuminate\Http\JsonResponse;

final class GetLessonsJsonController
{
    public function __invoke(int $courseId): JsonResponse
    {
        $lessons = Lesson::query()
            ->whereHas('module', fn ($q) => $q->where('course_id', $courseId))
            ->whereHas('comments')
            ->with('module:id,name')
            ->orderBy('name')
            ->get(['id', 'name', 'module_id'])
            ->map(fn ($lesson) => [
                'id' => $lesson->id,
                'name' => $lesson->module->name . ' - ' . $lesson->name,
            ]);

        return response()->json($lessons);
    }
}
