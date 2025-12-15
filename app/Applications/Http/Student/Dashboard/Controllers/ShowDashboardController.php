<?php

namespace App\Applications\Http\Student\Dashboard\Controllers;

use App\Domains\Dashboard\ViewModels\StudentDashboardViewModel;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class ShowDashboardController
{
    public function __invoke(Request $request): View
    {
        $viewModel = new StudentDashboardViewModel($request->user());

        return view('student.dashboard', compact('viewModel'));
    }
}
