<?php

namespace App\Applications\Http\Admin\Course\Controllers;

use App\Domains\Course\Models\Course;
use App\Domains\Course\ViewModels\CourseDetailViewModel;
use Illuminate\View\View;

final class ShowCourseController
{
    public function __invoke(Course $course): View
    {
        $course->load(['teacher', 'author', 'tags', 'students']);

        $viewModel = new CourseDetailViewModel($course);

        return view('admin.courses.show', compact('viewModel', 'course'));
    }
}
