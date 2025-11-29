<?php

namespace App\Applications\Http\Admin\Student\Controllers;

use App\Domains\Student\Data\StudentFilterData;
use App\Domains\Student\ViewModels\StudentListViewModel;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class GetStudentsController
{
    public function __invoke(Request $request): View
    {
        $user = $request->user('admin');
        $filters = StudentFilterData::validateAndCreate($request->all());

        $restrictToCourseIds = $user->isTeacher() ? $user->getTeacherCourseIds() : null;

        $viewModel = new StudentListViewModel($filters, perPage: 20, restrictToCourseIds: $restrictToCourseIds);

        return view('admin.students.index', compact('viewModel'));
    }
}
