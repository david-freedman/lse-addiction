<?php

namespace App\Applications\Http\Admin\StudentGroup\Controllers;

use App\Domains\Course\Models\Course;
use App\Domains\Student\Models\StudentGroup;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class EditStudentGroupController
{
    public function __invoke(Request $request, StudentGroup $studentGroup): View
    {
        $user = $request->user('admin');

        $coursesQuery = Course::orderBy('name');
        if ($user->isTeacher()) {
            $coursesQuery->whereIn('id', $user->getTeacherCourseIds());
        }
        $courses = $coursesQuery->get();

        return view('admin.student-groups.edit', compact('studentGroup', 'courses'));
    }
}
