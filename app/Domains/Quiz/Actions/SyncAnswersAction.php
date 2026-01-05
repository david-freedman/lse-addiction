<?php

namespace App\Domains\Quiz\Actions;

use App\Domains\Quiz\Models\QuizQuestion;
use Illuminate\Support\Facades\Storage;

class SyncAnswersAction
{
    public static function execute(QuizQuestion $question, array $answersData): void
    {
        $existingAnswerIds = $question->answers()->pluck('id')->toArray();
        $submittedAnswerIds = [];

        foreach ($answersData as $index => $answerData) {
            $answerId = $answerData['id'] ?? null;
            $answerImagePath = null;

            if (isset($answerData['answer_image']) && $answerData['answer_image'] instanceof \Illuminate\Http\UploadedFile) {
                $answerImagePath = $answerData['answer_image']->store('quiz/answers', 'public');
            } elseif (isset($answerData['existing_image'])) {
                $answerImagePath = $answerData['existing_image'];
            }

            $answerAttributes = [
                'answer_text' => $answerData['answer_text'] ?? null,
                'answer_image' => $answerImagePath,
                'is_correct' => (bool) ($answerData['is_correct'] ?? false),
                'category' => $answerData['category'] ?? null,
                'order' => $index + 1,
                'correct_order' => $answerData['correct_order'] ?? null,
            ];

            if ($answerId && in_array($answerId, $existingAnswerIds)) {
                $existingAnswer = $question->answers()->find($answerId);
                if ($existingAnswer) {
                    if ($answerImagePath !== $existingAnswer->answer_image && $existingAnswer->answer_image) {
                        Storage::disk('public')->delete($existingAnswer->answer_image);
                    }
                    $existingAnswer->update($answerAttributes);
                    $submittedAnswerIds[] = $answerId;
                }
            } else {
                $newAnswer = $question->answers()->create($answerAttributes);
                $submittedAnswerIds[] = $newAnswer->id;
            }
        }

        $answersToDelete = array_diff($existingAnswerIds, $submittedAnswerIds);
        foreach ($answersToDelete as $answerIdToDelete) {
            $answer = $question->answers()->find($answerIdToDelete);
            if ($answer) {
                if ($answer->answer_image) {
                    Storage::disk('public')->delete($answer->answer_image);
                }
                $answer->delete();
            }
        }
    }
}
