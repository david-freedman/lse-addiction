<?php

namespace App\Applications\Http\Admin\Teacher\Controllers;

use App\Domains\Teacher\Models\Teacher;
use Illuminate\View\View;

final class EditTeacherController
{
    public function __invoke(Teacher $teacher): View
    {
        $teacher->load('user');

        return view('admin.teachers.edit', compact('teacher'));
    }
}
