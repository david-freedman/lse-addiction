<?php

namespace App\Applications\Http\Student\Course\Controllers;

use App\Domains\Course\Models\Course;
use App\Domains\Course\ViewModels\CourseDetailViewModel;
use App\Domains\Course\ViewModels\CourseProgressViewModel;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class ShowCourseController
{
    public function __invoke(Request $request, Course $course): View
    {
        $student = $request->user();
        $isEnrolled = $student->courses()->where('course_id', $course->id)->exists();

        if ($course->isDraft() || $course->isHidden()) {
            abort(404);
        }

        if ($course->isArchived() && !$isEnrolled) {
            abort(404);
        }

        if ($isEnrolled) {
            if (! $course->hasStarted()) {
                $course->load(['teacher', 'author', 'tags']);
                $viewModel = new CourseDetailViewModel($course, $student);

                return view('student.courses.not-started', compact('viewModel', 'course'));
            }

            $course->load([
                'modules' => fn ($q) => $q->active()->ordered()
                    ->with(['lessons' => fn ($l) => $l->published()->ordered()]),
            ]);

            $viewModel = new CourseProgressViewModel($course, $student);

            return view('student.courses.progress', compact('viewModel'));
        }

        $course->load(['teacher', 'author', 'tags']);
        $viewModel = new CourseDetailViewModel($course, $student);

        return view('student.courses.show', compact('viewModel', 'course'));
    }
}
