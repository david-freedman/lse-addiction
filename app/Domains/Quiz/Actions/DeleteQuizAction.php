<?php

namespace App\Domains\Quiz\Actions;

use App\Domains\ActivityLog\Actions\LogActivityAction;
use App\Domains\ActivityLog\Data\ActivityLogData;
use App\Domains\ActivityLog\Enums\ActivitySubject;
use App\Domains\ActivityLog\Enums\ActivityType;
use App\Domains\Lesson\Models\Lesson;
use App\Domains\Quiz\Models\Quiz;

class DeleteQuizAction
{
    public static function execute(Quiz $quiz): void
    {
        $attributes = [
            'id' => $quiz->id,
            'title' => $quiz->title,
            'is_survey' => $quiz->is_survey,
        ];
        $courseId = self::getCourseId($quiz);

        $quiz->questions()->each(function ($question) {
            $question->answers()->delete();
        });
        $quiz->questions()->delete();
        $quiz->delete();

        LogActivityAction::execute(ActivityLogData::from([
            'subject_type' => ActivitySubject::Quiz,
            'subject_id' => $attributes['id'],
            'activity_type' => ActivityType::QuizDeleted,
            'description' => 'Quiz deleted',
            'properties' => ['attributes' => $attributes],
            'course_id' => $courseId,
        ]));
    }

    private static function getCourseId(Quiz $quiz): ?int
    {
        $quizzable = $quiz->quizzable;

        if ($quizzable instanceof Lesson) {
            return $quizzable->module?->course_id;
        }

        return $quizzable?->course_id;
    }
}
