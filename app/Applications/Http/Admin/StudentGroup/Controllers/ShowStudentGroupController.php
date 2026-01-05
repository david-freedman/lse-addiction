<?php

namespace App\Applications\Http\Admin\StudentGroup\Controllers;

use App\Domains\Student\Models\StudentGroup;
use App\Domains\Student\ViewModels\StudentGroupDetailViewModel;
use Illuminate\View\View;

final class ShowStudentGroupController
{
    public function __invoke(StudentGroup $studentGroup): View
    {
        $viewModel = new StudentGroupDetailViewModel($studentGroup);

        return view('admin.student-groups.show', compact('viewModel', 'studentGroup'));
    }
}
