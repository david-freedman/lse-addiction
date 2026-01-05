<?php

namespace App\Applications\Http\Admin\Course\Controllers;

use App\Domains\Teacher\Models\Teacher;
use Illuminate\View\View;

final class CreateCourseController
{
    public function __invoke(): View
    {
        $teachers = Teacher::orderBy('last_name')->get();

        $breadcrumbs = [
            ['title' => 'Курси', 'url' => route('admin.courses.index')],
            ['title' => 'Створити курс'],
        ];

        return view('admin.courses.create', compact('teachers', 'breadcrumbs'));
    }
}
