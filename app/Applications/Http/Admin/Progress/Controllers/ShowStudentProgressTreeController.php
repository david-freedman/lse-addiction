<?php

namespace App\Applications\Http\Admin\Progress\Controllers;

use App\Domains\Course\Models\Course;
use App\Domains\Progress\ViewModels\Admin\StudentProgressTreeViewModel;
use App\Domains\Student\Models\Student;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShowStudentProgressTreeController
{
    public function __invoke(Request $request): View
    {
        $user = $request->user('admin');
        $restrictToCourseIds = $user->isTeacher() ? $user->getTeacherCourseIds() : null;

        $course = $request->filled('course_id')
            ? Course::find($request->course_id)
            : null;

        $student = $request->filled('student_id')
            ? Student::find($request->student_id)
            : null;

        $viewModel = new StudentProgressTreeViewModel($course, $student, $restrictToCourseIds);

        return view('admin.progress.tree', [
            'viewModel' => $viewModel,
        ]);
    }
}
