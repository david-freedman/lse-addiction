<?php

namespace App\Applications\Http\Admin\Comment\Controllers;

use App\Domains\Lesson\Actions\CreateAdminReplyAction;
use App\Domains\Lesson\Data\CreateCommentData;
use App\Domains\Lesson\Models\LessonComment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class StoreReplyController
{
    public function __invoke(Request $request, LessonComment $comment): RedirectResponse
    {
        $user = $request->user('admin');

        if ($user->isTeacher()) {
            $course = $comment->lesson->module->course;
            if ($course->teacher_id !== $user->teacher?->id) {
                abort(403, 'Ви не маєте доступу до цього коментаря');
            }
        }

        $data = CreateCommentData::from($request->all());

        CreateAdminReplyAction::execute(
            user: $user,
            parentComment: $comment,
            data: $data
        );

        return redirect()
            ->back()
            ->with('success', 'Відповідь додано');
    }
}
