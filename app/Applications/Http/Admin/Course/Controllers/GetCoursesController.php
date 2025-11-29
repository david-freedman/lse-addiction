<?php

namespace App\Applications\Http\Admin\Course\Controllers;

use App\Domains\Course\Models\Course;
use App\Domains\Course\ViewModels\CourseListViewModel;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class GetCoursesController
{
    public function __invoke(Request $request): View
    {
        $user = $request->user('admin');

        $query = Course::with(['teacher', 'tags']);

        if ($user->isTeacher()) {
            $query->where('teacher_id', $user->teacherProfile?->id);
        }

        $courses = $query->orderBy('created_at', 'desc')->paginate(15);

        $viewModel = new CourseListViewModel($courses);

        return view('admin.courses.index', compact('viewModel'));
    }
}
