<?php

namespace App\Applications\Http\Admin\Teacher\Controllers;

use App\Domains\Teacher\Data\TeacherFilterData;
use App\Domains\Teacher\ViewModels\TeacherListViewModel;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class GetTeachersController
{
    public function __invoke(Request $request): View
    {
        $filters = TeacherFilterData::validateAndCreate($request->all());

        $viewModel = new TeacherListViewModel($filters, perPage: 20);

        return view('admin.teachers.index', compact('viewModel'));
    }
}
