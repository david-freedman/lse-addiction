<?php

namespace App\Applications\Http\Student\Lesson\Controllers;

use App\Domains\Course\Models\Course;
use App\Domains\Lesson\Models\Lesson;
use App\Domains\Progress\Actions\MarkLessonCompletedAction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class CompleteLessonController
{
    public function __construct(
        private MarkLessonCompletedAction $markLessonCompletedAction
    ) {}

    public function __invoke(Request $request, Course $course, Lesson $lesson): RedirectResponse
    {
        $student = $request->user();

        if (! $student->hasAccessToCourse($course)) {
            abort(403, 'Ви не маєте доступу до цього курсу');
        }

        if ($lesson->module->course_id !== $course->id) {
            throw new NotFoundHttpException('Урок не належить до цього курсу');
        }

        ($this->markLessonCompletedAction)($student, $lesson, $course);

        return back()->with('success', 'Урок позначено як завершений');
    }
}
