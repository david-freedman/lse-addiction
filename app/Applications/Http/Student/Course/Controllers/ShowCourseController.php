<?php

namespace App\Applications\Http\Student\Course\Controllers;

use App\Domains\Course\Models\Course;
use App\Domains\Course\ViewModels\CourseDetailViewModel;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class ShowCourseController
{
    public function __invoke(Request $request, Course $course): View
    {
        $student = $request->user();
        $course->load(['teacher', 'author', 'tags']);

        $viewModel = new CourseDetailViewModel($course, $student);

        return view('student.courses.show', compact('viewModel', 'course'));
    }
}
