<?php

namespace App\Domains\Quiz\Actions;

use App\Domains\Quiz\Models\Quiz;

class ReorderQuestionsAction
{
    public static function execute(Quiz $quiz, array $questionIds): void
    {
        foreach ($questionIds as $order => $questionId) {
            $quiz->questions()
                ->where('id', $questionId)
                ->update(['order' => $order + 1]);
        }
    }
}
