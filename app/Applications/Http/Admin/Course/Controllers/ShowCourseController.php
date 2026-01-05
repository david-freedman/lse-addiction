<?php

namespace App\Applications\Http\Admin\Course\Controllers;

use App\Domains\Course\Models\Course;
use App\Domains\Course\ViewModels\AdminCourseDetailViewModel;
use Illuminate\View\View;

final class ShowCourseController
{
    public function __invoke(Course $course): View
    {
        $viewModel = new AdminCourseDetailViewModel($course);
        $course = $viewModel->course();

        return view('admin.courses.show', [
            'viewModel' => $viewModel,
            'course' => $course,
            'tree' => $viewModel->tree(),
            'statistics' => $viewModel->statistics(),
            'breadcrumbs' => [
                ['title' => 'Курси', 'url' => route('admin.courses.index')],
                ['title' => $course->name],
            ],
        ]);
    }
}
