<?php

namespace App\Domains\Quiz\Actions;

use App\Domains\ActivityLog\Actions\LogActivityAction;
use App\Domains\ActivityLog\Data\ActivityLogData;
use App\Domains\ActivityLog\Enums\ActivitySubject;
use App\Domains\ActivityLog\Enums\ActivityType;
use App\Domains\Lesson\Data\CreateLessonData;
use App\Domains\Lesson\Models\Lesson;
use App\Domains\Quiz\Models\Quiz;

class CreateQuizForLessonAction
{
    public static function execute(Lesson $lesson, CreateLessonData $data, bool $isSurvey = false): Quiz
    {
        $quiz = $lesson->quiz()->create([
            'title' => $lesson->name,
            'passing_score' => $isSurvey ? 0 : ($data->quiz_passing_score ?? 70),
            'max_attempts' => $data->quiz_max_attempts,
            'time_limit_minutes' => $data->quiz_time_limit_minutes,
            'show_correct_answers' => $isSurvey ? false : ($data->quiz_show_correct_answers ?? true),
            'is_survey' => $isSurvey,
        ]);

        LogActivityAction::execute(ActivityLogData::from([
            'subject_type' => ActivitySubject::Quiz,
            'subject_id' => $quiz->id,
            'activity_type' => ActivityType::QuizCreated,
            'description' => 'Quiz created',
            'properties' => [
                'attributes' => array_filter([
                    'title' => $lesson->name,
                    'passing_score' => $quiz->passing_score,
                    'max_attempts' => $quiz->max_attempts,
                    'time_limit_minutes' => $quiz->time_limit_minutes,
                    'is_survey' => $isSurvey,
                ], fn ($v) => $v !== null),
            ],
            'course_id' => $lesson->module->course_id,
        ]));

        return $quiz;
    }
}
