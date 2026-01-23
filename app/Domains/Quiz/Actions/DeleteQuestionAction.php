<?php

namespace App\Domains\Quiz\Actions;

use App\Domains\ActivityLog\Actions\LogActivityAction;
use App\Domains\ActivityLog\Data\ActivityLogData;
use App\Domains\ActivityLog\Enums\ActivitySubject;
use App\Domains\ActivityLog\Enums\ActivityType;
use App\Domains\Lesson\Models\Lesson;
use App\Domains\Quiz\Models\QuizQuestion;
use Illuminate\Support\Facades\Storage;

class DeleteQuestionAction
{
    public static function execute(QuizQuestion $question): void
    {
        $quiz = $question->quiz;
        $questionData = [
            'id' => $question->id,
            'text' => $question->question_text,
            'type' => $question->type?->value,
        ];

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

        $quizzable = $quiz->quizzable;
        $courseId = $quizzable instanceof Lesson
            ? $quizzable->module?->course_id
            : $quizzable?->course_id;

        LogActivityAction::execute(ActivityLogData::from([
            'subject_type' => ActivitySubject::Quiz,
            'subject_id' => $quiz->id,
            'activity_type' => ActivityType::QuizQuestionDeleted,
            'description' => 'Quiz question deleted',
            'properties' => [
                'questions' => [
                    'removed' => [$questionData],
                ],
            ],
            'course_id' => $courseId,
        ]));
    }
}
