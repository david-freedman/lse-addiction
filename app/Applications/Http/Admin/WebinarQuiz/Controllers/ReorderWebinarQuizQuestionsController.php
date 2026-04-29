<?php

namespace App\Applications\Http\Admin\WebinarQuiz\Controllers;

use App\Domains\Quiz\Actions\ReorderQuestionsAction;
use App\Domains\Webinar\Models\Webinar;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class ReorderWebinarQuizQuestionsController
{
    public function __invoke(Request $request, Webinar $webinar): JsonResponse
    {
        $request->validate([
            'questions' => 'required|array',
            'questions.*' => 'integer|exists:quiz_questions,id',
        ]);

        ReorderQuestionsAction::execute($webinar->quiz, $request->input('questions'));

        return response()->json(['success' => true]);
    }
}
