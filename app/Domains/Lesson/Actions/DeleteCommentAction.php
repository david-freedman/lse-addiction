<?php

namespace App\Domains\Lesson\Actions;

use App\Domains\Lesson\Models\LessonComment;

final class DeleteCommentAction
{
    public static function execute(LessonComment $comment): bool
    {
        return $comment->delete();
    }
}
