<?php

namespace App\Domains\Lesson\Actions;

use App\Domains\Lesson\Models\Lesson;

class DeleteLessonAction
{
    public static function execute(Lesson $lesson): bool
    {
        return $lesson->delete();
    }
}
