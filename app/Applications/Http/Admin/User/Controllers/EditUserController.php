<?php

namespace App\Applications\Http\Admin\User\Controllers;

use App\Domains\User\ViewModels\UserDetailViewModel;
use App\Models\User;
use Illuminate\View\View;

final class EditUserController
{
    public function __invoke(User $user): View
    {
        $viewModel = new UserDetailViewModel($user);

        return view('admin.users.edit', compact('viewModel'));
    }
}
