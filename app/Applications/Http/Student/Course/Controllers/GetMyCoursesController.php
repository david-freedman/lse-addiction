<?php

namespace App\Applications\Http\Student\Course\Controllers;

use App\Domains\Course\ViewModels\MyCoursesViewModel;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class GetMyCoursesController
{
    public function __invoke(Request $request): View
    {
        $student = $request->user();
        $status = $request->query('status');
        $viewModel = new MyCoursesViewModel($student, $status);

        return view('student.courses.index', compact('viewModel'));
    }
}
