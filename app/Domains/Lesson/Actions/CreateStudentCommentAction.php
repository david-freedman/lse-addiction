<?php

namespace App\Domains\Lesson\Actions;

use App\Domains\Lesson\Data\CreateCommentData;
use App\Domains\Lesson\Models\Lesson;
use App\Domains\Lesson\Models\LessonComment;
use App\Domains\Student\Models\Student;

final class CreateStudentCommentAction
{
    public static function execute(Student $student, Lesson $lesson, CreateCommentData $data): LessonComment
    {
        return LessonComment::create([
            'student_id' => $student->id,
            'lesson_id' => $lesson->id,
            'parent_id' => $data->parent_id,
            'content' => $data->content,
        ]);
    }
}
