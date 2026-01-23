<?php

namespace App\Applications\Http\Admin\Dashboard\Controllers;

use App\Domains\Dashboard\ViewModels\AdminDashboardViewModel;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class ShowDashboardController
{
    public function __invoke(Request $request): View
    {
        $viewModel = new AdminDashboardViewModel($request->user('admin'));

        return view('admin.dashboard', compact('viewModel'));
    }
}
