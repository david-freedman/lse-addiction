<?php

namespace App\Applications\Http\Student\Dashboard\Controllers;

use App\Domains\Course\ViewModels\StudentDashboardViewModel;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class ShowDashboardController
{
    public function __invoke(Request $request): View
    {
        $student = $request->user();

        $course = $student->courses()
            ->wherePivot('status', 'active')
            ->with([
                'modules' => fn ($q) => $q->active()->ordered()
                    ->with(['lessons' => fn ($l) => $l->published()->ordered()]),
            ])
            ->orderBy('course_student.enrolled_at', 'desc')
            ->first();

        $viewModel = new StudentDashboardViewModel($course, $student);

        return view('student.dashboard', compact('viewModel'));
    }
}
