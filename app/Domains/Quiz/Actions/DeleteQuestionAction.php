<?php

namespace App\Domains\Quiz\Actions;

use App\Domains\Quiz\Models\QuizQuestion;
use Illuminate\Support\Facades\Storage;

class DeleteQuestionAction
{
    public static function execute(QuizQuestion $question): void
    {
        if ($question->question_image) {
            Storage::disk('public')->delete($question->question_image);
        }

        foreach ($question->answers as $answer) {
            if ($answer->answer_image) {
                Storage::disk('public')->delete($answer->answer_image);
            }
        }

        $question->answers()->delete();
        $question->delete();
    }
}
