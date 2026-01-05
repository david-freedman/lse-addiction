<?php

namespace App\Domains\Quiz\Actions;

use App\Domains\Quiz\Models\Quiz;

class DeleteQuizAction
{
    public static function execute(Quiz $quiz): void
    {
        $quiz->questions()->each(function ($question) {
            $question->answers()->delete();
        });
        $quiz->questions()->delete();
        $quiz->delete();
    }
}
