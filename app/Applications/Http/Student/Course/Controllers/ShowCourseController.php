<?php

namespace App\Applications\Http\Student\Course\Controllers;

use App\Domains\Course\Models\Course;
use App\Domains\Course\ViewModels\CourseDetailViewModel;
use App\Domains\Course\ViewModels\CourseProgressViewModel;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

final class ShowCourseController
{
    public function __invoke(Request $request, Course $course): View|RedirectResponse
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
            if (! $student->hasCompletedProfileStep()) {
                return redirect()->route('student.profile.show')
                    ->with('warning', 'Для доступу до контенту необхідно заповнити анкетні дані у вашому профілі. Для цього перейдіть в редагування профілю, вкладка "Анкетні дані".');
            }

            $requiresPayment = $course->price > 0 && ! $student->hasAccessToCourse($course);

            if (! $requiresPayment && ! $course->hasStarted()) {
                $course->load(['teachers', 'author', 'tags']);
                $viewModel = new CourseDetailViewModel($course, $student);

                return view('student.courses.not-started', compact('viewModel', 'course'));
            }

            $course->load([
                'modules' => fn ($q) => $q->active()->ordered()
                    ->with(['lessons' => fn ($l) => $l->published()->ordered()]),
            ]);

            $viewModel = new CourseProgressViewModel($course, $student);

            return view('student.courses.progress', compact('viewModel', 'requiresPayment', 'course'));
        }

        $course->load(['teachers', 'author', 'tags']);
        $viewModel = new CourseDetailViewModel($course, $student);

        return view('student.courses.show', compact('viewModel', 'course'));
    }
}
