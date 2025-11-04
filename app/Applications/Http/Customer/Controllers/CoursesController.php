<?php

namespace App\Applications\Http\Customer\Controllers;

use App\Domains\Course\Models\Course;
use App\Domains\Course\ViewModels\CourseListViewModel;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CoursesController
{
    public function index(Request $request): View
    {
        $courses = Course::with(['coach', 'tags'])
            ->where('status', 'published')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        $viewModel = new CourseListViewModel($courses);

        return view('customer.courses.browse', compact('viewModel'));
    }
}
