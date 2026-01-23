<?php

namespace App\Domains\Quiz\Actions;

use App\Domains\ActivityLog\Actions\LogActivityAction;
use App\Domains\ActivityLog\Data\ActivityLogData;
use App\Domains\ActivityLog\Enums\ActivitySubject;
use App\Domains\ActivityLog\Enums\ActivityType;
use App\Domains\Lesson\Models\Lesson;
use App\Domains\Quiz\Data\CreateQuestionData;
use App\Domains\Quiz\Models\Quiz;
use App\Domains\Quiz\Models\QuizQuestion;

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

        $quizzable = $quiz->quizzable;
        $courseId = $quizzable instanceof Lesson
            ? $quizzable->module?->course_id
            : $quizzable?->course_id;

        LogActivityAction::execute(ActivityLogData::from([
            'subject_type' => ActivitySubject::Quiz,
            'subject_id' => $quiz->id,
            'activity_type' => ActivityType::QuizQuestionCreated,
            'description' => 'Quiz question created',
            'properties' => [
                'questions' => [
                    'added' => [
                        ['id' => $question->id, 'text' => $data->question_text, 'type' => $data->type?->value],
                    ],
                ],
            ],
            'course_id' => $courseId,
        ]));

        return $question->load('answers');
    }
}
