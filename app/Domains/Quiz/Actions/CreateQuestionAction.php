<?php

namespace App\Domains\Quiz\Actions;

use App\Domains\Quiz\Data\CreateQuestionData;
use App\Domains\Quiz\Models\Quiz;
use App\Domains\Quiz\Models\QuizQuestion;
use Illuminate\Support\Facades\Storage;

class CreateQuestionAction
{
    public static function execute(Quiz $quiz, CreateQuestionData $data): QuizQuestion
    {
        $questionImagePath = null;
        if ($data->question_image) {
            $questionImagePath = $data->question_image->store('quiz/questions', 'public');
        }

        $question = $quiz->questions()->create([
            'type' => $data->type,
            'question_text' => $data->question_text,
            'question_image' => $questionImagePath,
            'order' => $quiz->questions()->max('order') + 1,
            'points' => $data->points,
        ]);

        SyncAnswersAction::execute($question, $data->answers);

        return $question->load('answers');
    }
}
