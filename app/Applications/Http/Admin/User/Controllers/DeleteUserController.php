<?php

namespace App\Applications\Http\Admin\User\Controllers;

use App\Domains\User\Actions\DeleteUserAction;
use App\Models\User;
use Illuminate\Http\RedirectResponse;

final class DeleteUserController
{
    public function __invoke(User $user): RedirectResponse
    {
        DeleteUserAction::execute($user);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Користувача успішно видалено');
    }
}
