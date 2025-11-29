<?php

namespace App\Applications\Http\Admin\User\Controllers;

use App\Domains\User\Data\UserFilterData;
use App\Domains\User\ViewModels\UserListViewModel;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class GetUsersController
{
    public function __invoke(Request $request): View
    {
        $filters = UserFilterData::validateAndCreate($request->all());

        $viewModel = new UserListViewModel($filters, perPage: 20);

        return view('admin.users.index', compact('viewModel'));
    }
}
