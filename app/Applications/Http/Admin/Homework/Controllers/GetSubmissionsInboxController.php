<?php

namespace App\Applications\Http\Admin\Homework\Controllers;

use App\Domains\Homework\Data\SubmissionsFilterData;
use App\Domains\Homework\ViewModels\Admin\SubmissionsInboxViewModel;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class GetSubmissionsInboxController
{
    public function __invoke(Request $request): View
    {
        $filters = SubmissionsFilterData::from([
            'status' => $request->string('status')->toString() ?: null,
            'course_id' => $request->integer('course_id') ?: null,
            'module_id' => $request->integer('module_id') ?: null,
            'lesson_id' => $request->integer('lesson_id') ?: null,
            'search' => $request->string('search')->toString() ?: null,
        ]);

        $viewModel = new SubmissionsInboxViewModel($filters);

        return view('admin.homework.index', compact('viewModel'));
    }
}
