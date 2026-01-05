<?php

namespace App\Domains\Lesson\Actions;

use App\Domains\Lesson\Models\Lesson;
use App\Domains\Lesson\Models\StudentLessonNote;
use App\Domains\Student\Models\Student;

final class SaveLessonNoteAction
{
    public static function execute(Student $student, Lesson $lesson, string $content): StudentLessonNote
    {
        return StudentLessonNote::updateOrCreate(
            [
                'student_id' => $student->id,
                'lesson_id' => $lesson->id,
            ],
            [
                'content' => $content,
            ]
        );
    }
}
