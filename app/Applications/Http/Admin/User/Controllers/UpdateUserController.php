<?php

namespace App\Applications\Http\Admin\User\Controllers;

use App\Domains\User\Actions\UpdateUserAction;
use App\Domains\User\Data\UpdateUserData;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class UpdateUserController
{
    public function __invoke(Request $request, User $user): RedirectResponse
    {
        $data = UpdateUserData::validateAndCreate($request->all());

        UpdateUserAction::execute($user, $data);

        return redirect()
            ->route('admin.users.show', $user)
            ->with('success', 'Користувача успішно оновлено');
    }
}
