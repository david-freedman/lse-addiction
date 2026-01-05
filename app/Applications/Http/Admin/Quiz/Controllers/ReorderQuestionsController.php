<?php

namespace App\Applications\Http\Admin\Quiz\Controllers;

use App\Domains\Course\Models\Course;
use App\Domains\Lesson\Models\Lesson;
use App\Domains\Module\Models\Module;
use App\Domains\Quiz\Actions\ReorderQuestionsAction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class ReorderQuestionsController
{
    public function __invoke(Request $request, Course $course, Module $module, Lesson $lesson): JsonResponse
    {
        $request->validate([
            'questions' => 'required|array',
            'questions.*' => 'integer|exists:quiz_questions,id',
        ]);

        ReorderQuestionsAction::execute($lesson->quiz, $request->input('questions'));

        return response()->json(['success' => true]);
    }
}
