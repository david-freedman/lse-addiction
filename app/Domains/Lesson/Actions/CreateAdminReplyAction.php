<?php

namespace App\Domains\Lesson\Actions;

use App\Domains\Lesson\Data\CreateCommentData;
use App\Domains\Lesson\Models\LessonComment;
use App\Models\User;

final class CreateAdminReplyAction
{
    public static function execute(User $user, LessonComment $parentComment, CreateCommentData $data): LessonComment
    {
        return LessonComment::create([
            'user_id' => $user->id,
            'lesson_id' => $parentComment->lesson_id,
            'parent_id' => $parentComment->id,
            'content' => $data->content,
        ]);
    }
}
