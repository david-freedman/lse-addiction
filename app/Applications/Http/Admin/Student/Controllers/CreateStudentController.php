<?php

namespace App\Applications\Http\Admin\Student\Controllers;

use Illuminate\View\View;

final class CreateStudentController
{
    public function __invoke(): View
    {
        return view('admin.students.create');
    }
}
