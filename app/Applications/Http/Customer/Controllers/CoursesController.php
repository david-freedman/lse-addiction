<?php

namespace App\Applications\Http\Customer\Controllers;

use App\Domains\Course\Enums\CourseType;
use App\Domains\Course\Models\Course;
use App\Domains\Course\ViewModels\CourseListViewModel;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CoursesController
{
    public function index(Request $request): View
    {
        $filter = $request->query('filter')
            ? CourseType::tryFrom($request->query('filter'))
            : null;

        $courses = Course::with(['coach', 'tags'])
            ->where('status', 'published')
            ->byFilter($filter)
            ->orderBy('starts_at', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(6);

        $viewModel = new CourseListViewModel($courses, $filter);

        return view('customer.courses.browse', compact('viewModel'));
    }
}
