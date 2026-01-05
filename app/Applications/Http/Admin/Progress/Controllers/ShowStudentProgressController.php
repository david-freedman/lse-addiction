<?php

namespace App\Applications\Http\Admin\Progress\Controllers;

use App\Domains\Progress\ViewModels\AdminStudentProgressViewModel;
use App\Domains\Student\Models\Student;
use Illuminate\View\View;

final class ShowStudentProgressController
{
    public function __invoke(Student $student): View
    {
        $viewModel = new AdminStudentProgressViewModel($student);

        return view('admin.progress.student', compact('viewModel', 'student'));
    }
}
