<?php

namespace App\Applications\Http\Admin\Progress\Controllers;

use App\Domains\Progress\ViewModels\AdminProgressDashboardViewModel;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class ShowProgressDashboardController
{
    public function __invoke(Request $request): View
    {
        $period = $request->get('period', 'all');

        $user = $request->user('admin');
        $restrictToCourseIds = $user->isTeacher() ? $user->getTeacherCourseIds() : null;

        $viewModel = new AdminProgressDashboardViewModel($period, $restrictToCourseIds);

        return view('admin.progress.dashboard', compact('viewModel'));
    }
}
