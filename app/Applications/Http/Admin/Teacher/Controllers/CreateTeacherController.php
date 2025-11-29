<?php

namespace App\Applications\Http\Admin\Teacher\Controllers;

use Illuminate\View\View;

final class CreateTeacherController
{
    public function __invoke(): View
    {
        return view('admin.teachers.create');
    }
}
