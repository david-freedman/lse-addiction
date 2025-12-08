<?php

namespace App\Applications\Http\Student\Lesson\Controllers;

use App\Domains\Course\Models\Course;
use App\Domains\Lesson\Enums\LessonType;
use App\Domains\Lesson\Models\Lesson;
use App\Domains\Lesson\ViewModels\LessonDetailViewModel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class ShowLessonController
{
    public function __invoke(Request $request, Course $course, Lesson $lesson): View|RedirectResponse
    {
        $student = $request->user();

        if (! $student->hasAccessToCourse($course)) {
            abort(403, 'Ви не маєте доступу до цього курсу');
        }

        if ($lesson->module->course_id !== $course->id) {
            throw new NotFoundHttpException('Урок не належить до цього курсу');
        }

        if ($lesson->type === LessonType::Quiz) {
            return redirect()->route('student.quiz.show', [$course, $lesson]);
        }

        $lesson->load(['module']);

        $viewModel = new LessonDetailViewModel($lesson, $course, $student);

        return view('student.lessons.show', compact('viewModel', 'course', 'lesson'));
    }
}
