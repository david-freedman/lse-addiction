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
        $viewModel = new AdminProgressDashboardViewModel($period);

        return view('admin.progress.dashboard', compact('viewModel'));
    }
}
