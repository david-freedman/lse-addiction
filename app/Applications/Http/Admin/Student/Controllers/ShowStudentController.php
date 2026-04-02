<?php

namespace App\Applications\Http\Admin\Student\Controllers;

use App\Domains\Student\Models\Student;
use App\Domains\Student\ViewModels\StudentDetailViewModel;
use Illuminate\View\View;

final class ShowStudentController
{
    public function __invoke(int $id): View
    {
        $student = Student::withTrashed()->findOrFail($id);

        $viewModel = new StudentDetailViewModel($student);

        return view('admin.students.show', compact('viewModel', 'student'));
    }
}
