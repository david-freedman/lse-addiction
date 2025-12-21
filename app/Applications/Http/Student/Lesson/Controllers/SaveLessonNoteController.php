<?php

namespace App\Applications\Http\Student\Lesson\Controllers;

use App\Domains\Course\Models\Course;
use App\Domains\Lesson\Actions\SaveLessonNoteAction;
use App\Domains\Lesson\Models\Lesson;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class SaveLessonNoteController
{
    public function __invoke(Request $request, Course $course, Lesson $lesson): JsonResponse
    {
        $student = $request->user();

        if (! $student->hasAccessToCourse($course)) {
            return response()->json([
                'success' => false,
                'message' => 'Ви не маєте доступу до цього курсу',
            ], 403);
        }

        if ($lesson->module->course_id !== $course->id) {
            throw new NotFoundHttpException('Урок не належить до цього курсу');
        }

        $validated = $request->validate([
            'content' => ['nullable', 'string', 'max:65535'],
        ]);

        SaveLessonNoteAction::execute(
            student: $student,
            lesson: $lesson,
            content: $validated['content'] ?? ''
        );

        return response()->json([
            'success' => true,
            'message' => 'Нотатки збережено',
        ]);
    }
}
