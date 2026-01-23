<?php

namespace App\Domains\Quiz\Actions;

use App\Domains\ActivityLog\Actions\LogActivityAction;
use App\Domains\ActivityLog\Data\ActivityLogData;
use App\Domains\ActivityLog\Enums\ActivitySubject;
use App\Domains\ActivityLog\Enums\ActivityType;
use App\Domains\Lesson\Models\Lesson;
use App\Domains\Quiz\Data\UpdateQuestionData;
use App\Domains\Quiz\Models\QuizQuestion;
use Illuminate\Support\Facades\Storage;

class UpdateQuestionAction
{
    public static function execute(QuizQuestion $question, UpdateQuestionData $data): QuizQuestion
    {
        $oldValues = [
            'question_text' => $question->question_text,
            'type' => $question->type?->value,
            'points' => $question->points,
        ];

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

        $changes = [];
        $newValues = [
            'question_text' => $data->question_text,
            'type' => $data->type?->value,
            'points' => $data->points,
        ];
        foreach ($newValues as $key => $newValue) {
            if ($oldValues[$key] != $newValue) {
                $changes[$key] = ['old' => $oldValues[$key], 'new' => $newValue];
            }
        }

        if (! empty($changes)) {
            $quiz = $question->quiz;
            $quizzable = $quiz->quizzable;
            $courseId = $quizzable instanceof Lesson
                ? $quizzable->module?->course_id
                : $quizzable?->course_id;

            LogActivityAction::execute(ActivityLogData::from([
                'subject_type' => ActivitySubject::Quiz,
                'subject_id' => $quiz->id,
                'activity_type' => ActivityType::QuizQuestionUpdated,
                'description' => 'Quiz question updated',
                'properties' => [
                    'questions' => [
                        'updated' => [
                            ['id' => $question->id, 'changes' => $changes],
                        ],
                    ],
                ],
                'course_id' => $courseId,
            ]));
        }

        return $question->fresh()->load('answers');
    }
}
