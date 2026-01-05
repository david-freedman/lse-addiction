<?php

namespace App\Applications\Http\Admin\Course\Controllers;

use App\Domains\Course\Data\CourseFilterData;
use App\Domains\Course\ViewModels\AdminCourseListViewModel;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class GetCoursesController
{
    public function __invoke(Request $request): View
    {
        $filters = CourseFilterData::from($request->all());
        $viewModel = new AdminCourseListViewModel(
            filters: $filters,
            perPage: 20,
            user: $request->user('admin'),
        );

        return view('admin.courses.index', compact('viewModel'));
    }
}
