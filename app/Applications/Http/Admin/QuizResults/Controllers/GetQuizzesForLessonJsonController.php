<?php

namespace App\Applications\Http\Admin\QuizResults\Controllers;

use App\Domains\Lesson\Models\Lesson;
use App\Domains\Quiz\Models\Quiz;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class GetQuizzesForLessonJsonController
{
    public function __invoke(Request $request, int $lessonId): JsonResponse
    {
        $isSurvey = $request->query('tab') === 'surveys';

        $quizzes = Quiz::query()
            ->where('quizzable_type', Lesson::class)
            ->where('quizzable_id', $lessonId)
            ->where('is_survey', $isSurvey)
            ->whereHas('attempts')
            ->get(['id', 'title']);

        return response()->json($quizzes);
    }
}
