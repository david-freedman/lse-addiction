<?php

namespace App\Applications\Http\Admin\Student\Controllers;

use App\Domains\Student\Models\ProfileField;
use Illuminate\View\View;

final class CreateStudentController
{
    public function __invoke(): View
    {
        $fields = ProfileField::active()->ordered()->get();

        return view('admin.students.create', compact('fields'));
    }
}
