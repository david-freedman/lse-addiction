<?php

namespace App\Applications\Http\Admin\Comment\Controllers;

use App\Domains\Lesson\Actions\DeleteCommentAction;
use App\Domains\Lesson\Models\LessonComment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class DeleteCommentController
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

        DeleteCommentAction::execute($comment);

        return redirect()
            ->back()
            ->with('success', 'Коментар видалено');
    }
}
