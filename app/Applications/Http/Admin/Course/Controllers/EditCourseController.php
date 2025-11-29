<?php

namespace App\Applications\Http\Admin\Course\Controllers;

use App\Domains\Course\Models\Course;
use App\Domains\Teacher\Models\Teacher;
use Illuminate\View\View;

final class EditCourseController
{
    public function __invoke(Course $course): View
    {
        $course->load(['tags', 'author', 'teacher']);
        $teachers = Teacher::orderBy('last_name')->get();

        return view('admin.courses.edit', compact('course', 'teachers'));
    }
}
