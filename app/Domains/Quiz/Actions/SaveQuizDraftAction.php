<?php

namespace App\Domains\Quiz\Actions;

use App\Domains\Lesson\Models\Lesson;
use App\Domains\Progress\Models\StudentQuizDraft;
use App\Domains\Student\Models\Student;
use Carbon\Carbon;

final class SaveQuizDraftAction
{
    public function __invoke(
        Student $student,
        Lesson $lesson,
        array $answers,
        ?Carbon $startedAt = null
    ): StudentQuizDraft {
        return StudentQuizDraft::updateOrCreate(
            [
                'student_id' => $student->id,
                'lesson_id' => $lesson->id,
            ],
            [
                'answers' => $answers,
                'started_at' => $startedAt ?? now(),
            ]
        );
    }
}
