<?php

namespace App\Applications\Http\Student\Controllers;

use App\Domains\Course\Actions\EnrollStudentAction;
use App\Domains\Course\Actions\UnenrollStudentAction;
use App\Domains\Course\Models\Course;
use App\Domains\Course\ViewModels\CourseDetailViewModel;
use App\Domains\Course\ViewModels\MyCoursesViewModel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MyCoursesController
{
    public function index(Request $request): View
    {
        $student = $request->user();
        $status = $request->query('status');
        $viewModel = new MyCoursesViewModel($student, $status);

        return view('student.courses.index', compact('viewModel'));
    }

    public function show(Request $request, Course $course): View
    {
        $student = $request->user();
        $course->load(['coach', 'author', 'tags']);

        $viewModel = new CourseDetailViewModel($course, $student);

        return view('student.courses.show', compact('viewModel', 'course'));
    }

    public function enroll(Request $request, Course $course): RedirectResponse
    {
        $student = $request->user();

        if ($course->status !== 'published') {
            return redirect()
                ->back()
                ->with('error', 'Цей курс недоступний для запису');
        }

        EnrollStudentAction::execute($course, $student);

        return redirect()
            ->route('student.my-courses')
            ->with('success', 'Ви успішно записалися на курс');
    }

    public function unenroll(Request $request, Course $course): RedirectResponse
    {
        $student = $request->user();

        if ($student->hasPurchasedCourse($course)) {
            return redirect()
                ->back()
                ->with('error', 'Неможливо відписатись від придбаного курсу');
        }

        UnenrollStudentAction::execute($course, $student);

        return redirect()
            ->route('student.my-courses')
            ->with('success', 'Ви успішно скасували запис на курс');
    }
}
