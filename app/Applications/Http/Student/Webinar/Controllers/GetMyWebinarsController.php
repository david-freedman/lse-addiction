<?php

namespace App\Applications\Http\Student\Webinar\Controllers;

use App\Domains\Webinar\ViewModels\Student\MyWebinarsViewModel;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class GetMyWebinarsController
{
    public function __invoke(Request $request): View
    {
        $student = $request->user();
        $status = $request->query('status');
        $viewModel = new MyWebinarsViewModel($student, $status);

        return view('student.webinars.index', compact('viewModel'));
    }
}
