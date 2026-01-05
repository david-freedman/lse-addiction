<?php

namespace App\Applications\Http\Admin\StudentGroup\Controllers;

use App\Domains\Course\Models\Course;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class CreateStudentGroupController
{
    public function __invoke(Request $request): View
    {
        $user = $request->user('admin');

        $coursesQuery = Course::orderBy('name');
        if ($user->isTeacher()) {
            $coursesQuery->whereIn('id', $user->getTeacherCourseIds());
        }
        $courses = $coursesQuery->get();

        return view('admin.student-groups.create', compact('courses'));
    }
}
