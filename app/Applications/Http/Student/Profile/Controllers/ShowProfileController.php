<?php

namespace App\Applications\Http\Student\Profile\Controllers;

use App\Domains\Student\ViewModels\StudentProfileViewModel;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class ShowProfileController
{
    public function __invoke(Request $request): View
    {
        $viewModel = new StudentProfileViewModel($request->user());

        return view('student.profile.show', compact('viewModel'));
    }
}
