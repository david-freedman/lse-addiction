<?php

namespace App\Domains\Quiz\Actions;

use App\Domains\Lesson\Models\Lesson;
use App\Domains\Progress\Models\StudentQuizDraft;
use App\Domains\Student\Models\Student;

final class LoadQuizDraftAction
{
    public function __invoke(Student $student, Lesson $lesson): ?StudentQuizDraft
    {
        return StudentQuizDraft::where('student_id', $student->id)
            ->where('lesson_id', $lesson->id)
            ->first();
    }
}
