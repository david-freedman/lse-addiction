<?php

namespace App\Applications\Http\Admin\User\Controllers;

use App\Models\Enums\UserRole;
use Illuminate\View\View;

final class CreateUserController
{
    public function __invoke(): View
    {
        $roles = UserRole::cases();

        return view('admin.users.create', compact('roles'));
    }
}
