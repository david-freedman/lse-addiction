<?php

namespace App\Domains\Quiz\Actions;

use App\Domains\Quiz\Data\UpdateQuestionData;
use App\Domains\Quiz\Models\QuizQuestion;
use Illuminate\Support\Facades\Storage;

class UpdateQuestionAction
{
    public static function execute(QuizQuestion $question, UpdateQuestionData $data): QuizQuestion
    {
        $questionImagePath = $question->question_image;

        if ($data->remove_image && $questionImagePath) {
            Storage::disk('public')->delete($questionImagePath);
            $questionImagePath = null;
        }

        if ($data->question_image) {
            if ($question->question_image) {
                Storage::disk('public')->delete($question->question_image);
            }
            $questionImagePath = $data->question_image->store('quiz/questions', 'public');
        }

        $question->update([
            'type' => $data->type,
            'question_text' => $data->question_text,
            'question_image' => $questionImagePath,
            'points' => $data->points,
        ]);

        SyncAnswersAction::execute($question, $data->answers);

        return $question->fresh()->load('answers');
    }
}
