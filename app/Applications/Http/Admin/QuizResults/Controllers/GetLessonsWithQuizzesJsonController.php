<?php

namespace App\Applications\Http\Admin\QuizResults\Controllers;

use App\Domains\Lesson\Models\Lesson;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class GetLessonsWithQuizzesJsonController
{
    public function __invoke(Request $request, int $moduleId): JsonResponse
    {
        $isSurvey = $request->query('tab') === 'surveys';

        $lessons = Lesson::query()
            ->where('module_id', $moduleId)
            ->whereHas('quiz', fn ($q) => $q->where('is_survey', $isSurvey))
            ->whereHas('quiz.attempts')
            ->orderBy('order')
            ->get(['id', 'name', 'order']);

        return response()->json($lessons);
    }
}
