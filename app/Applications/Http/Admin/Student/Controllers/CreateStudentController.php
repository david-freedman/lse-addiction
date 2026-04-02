<?php

namespace App\Applications\Http\Admin\Student\Controllers;

use App\Domains\Student\Models\Specialty;
use Illuminate\View\View;

final class CreateStudentController
{
    public function __invoke(): View
    {
        $specialties = Specialty::orderBy('name')->get();

        return view('admin.students.create', compact('specialties'));
    }
}
