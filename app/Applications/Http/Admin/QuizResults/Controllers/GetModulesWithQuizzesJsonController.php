<?php

namespace App\Applications\Http\Admin\QuizResults\Controllers;

use App\Domains\Module\Models\Module;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class GetModulesWithQuizzesJsonController
{
    public function __invoke(Request $request, int $courseId): JsonResponse
    {
        $isSurvey = $request->query('tab') === 'surveys';

        $modules = Module::query()
            ->where('course_id', $courseId)
            ->whereHas('lessons.quiz', fn ($q) => $q->where('is_survey', $isSurvey))
            ->whereHas('lessons.quiz.attempts')
            ->orderBy('order')
            ->get(['id', 'name', 'order']);

        return response()->json($modules);
    }
}
