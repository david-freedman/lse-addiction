<?php

namespace App\Applications\Http\Admin\Progress\Controllers;

use App\Domains\Course\Models\Course;
use App\Domains\Progress\ViewModels\AdminCourseProgressViewModel;
use Illuminate\View\View;

final class ShowCourseProgressController
{
    public function __invoke(Course $course): View
    {
        $viewModel = new AdminCourseProgressViewModel($course);

        return view('admin.progress.course', compact('viewModel', 'course'));
    }
}
