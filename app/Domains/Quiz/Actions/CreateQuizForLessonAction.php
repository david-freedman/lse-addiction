<?php

namespace App\Domains\Quiz\Actions;

use App\Domains\Lesson\Data\CreateLessonData;
use App\Domains\Lesson\Models\Lesson;
use App\Domains\Quiz\Models\Quiz;

class CreateQuizForLessonAction
{
    public static function execute(Lesson $lesson, CreateLessonData $data, bool $isSurvey = false): Quiz
    {
        return $lesson->quiz()->create([
            'title' => $lesson->name,
            'passing_score' => $isSurvey ? 0 : ($data->quiz_passing_score ?? 70),
            'max_attempts' => $data->quiz_max_attempts,
            'time_limit_minutes' => $data->quiz_time_limit_minutes,
            'show_correct_answers' => $isSurvey ? false : ($data->quiz_show_correct_answers ?? true),
            'is_final' => $isSurvey ? false : ($data->quiz_is_final ?? false),
            'is_survey' => $isSurvey,
        ]);
    }
}
