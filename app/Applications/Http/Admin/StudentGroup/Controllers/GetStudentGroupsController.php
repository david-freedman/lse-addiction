<?php

namespace App\Applications\Http\Admin\StudentGroup\Controllers;

use App\Domains\Student\Data\StudentGroupFilterData;
use App\Domains\Student\ViewModels\StudentGroupListViewModel;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class GetStudentGroupsController
{
    public function __invoke(Request $request): View
    {
        $user = $request->user('admin');
        $filters = StudentGroupFilterData::validateAndCreate($request->all());

        $restrictToCourseIds = $user->isTeacher() ? $user->getTeacherCourseIds() : null;

        $viewModel = new StudentGroupListViewModel($filters, perPage: 20, restrictToCourseIds: $restrictToCourseIds);

        return view('admin.student-groups.index', compact('viewModel'));
    }
}
