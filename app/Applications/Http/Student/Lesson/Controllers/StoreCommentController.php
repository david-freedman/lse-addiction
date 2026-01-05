<?php

namespace App\Applications\Http\Student\Lesson\Controllers;

use App\Domains\Course\Models\Course;
use App\Domains\Lesson\Actions\CreateStudentCommentAction;
use App\Domains\Lesson\Data\CreateCommentData;
use App\Domains\Lesson\Models\Lesson;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class StoreCommentController
{
    public function __invoke(Request $request, Course $course, Lesson $lesson): RedirectResponse
    {
        $student = $request->user();

        if (!$student->hasAccessToCourse($course)) {
            abort(403, 'Ви не маєте доступу до цього курсу');
        }

        if ($lesson->module->course_id !== $course->id) {
            throw new NotFoundHttpException('Урок не належить до цього курсу');
        }

        $data = CreateCommentData::from($request->all());

        CreateStudentCommentAction::execute(
            student: $student,
            lesson: $lesson,
            data: $data
        );

        return redirect()
            ->back()
            ->with('success', 'Коментар додано');
    }
}
