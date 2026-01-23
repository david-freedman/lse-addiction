<?php

namespace App\Applications\Http\Student\Quiz\Controllers;

use App\Domains\Course\Models\Course;
use App\Domains\Lesson\Models\Lesson;
use App\Domains\Quiz\Actions\SaveQuizDraftAction;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class SaveDraftController
{
    public function __construct(
        private readonly SaveQuizDraftAction $saveQuizDraftAction
    ) {}

    public function __invoke(Request $request, Course $course, Lesson $lesson): JsonResponse
    {
        $student = $request->user();

        if (!$student->hasAccessToCourse($course)) {
            return response()->json(['error' => 'Немає доступу'], 403);
        }

        if ($lesson->module->course_id !== $course->id) {
            throw new NotFoundHttpException('Урок не належить до цього курсу');
        }

        $answers = $request->input('answers', []);
        $startedAt = session('quiz_started_at');

        if ($startedAt instanceof string) {
            $startedAt = Carbon::parse($startedAt);
        }

        ($this->saveQuizDraftAction)($student, $lesson, $answers, $startedAt);

        return response()->json(['success' => true]);
    }
}
