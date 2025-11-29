<?php

namespace App\Applications\Http\Admin\Student\Controllers;

use App\Domains\Student\Models\Student;
use Illuminate\View\View;

final class EditStudentController
{
    public function __invoke(Student $student): View
    {
        return view('admin.students.edit', compact('student'));
    }
}
