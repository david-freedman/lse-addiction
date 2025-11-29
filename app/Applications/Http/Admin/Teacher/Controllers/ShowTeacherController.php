<?php

namespace App\Applications\Http\Admin\Teacher\Controllers;

use App\Domains\Teacher\Models\Teacher;
use App\Domains\Teacher\ViewModels\TeacherDetailViewModel;
use Illuminate\View\View;

final class ShowTeacherController
{
    public function __invoke(Teacher $teacher): View
    {
        $viewModel = new TeacherDetailViewModel($teacher);

        return view('admin.teachers.show', compact('viewModel'));
    }
}
