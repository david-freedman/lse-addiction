<?php

namespace App\Applications\Http\Admin\User\Controllers;

use App\Domains\User\Actions\CreateUserAction;
use App\Domains\User\Data\CreateUserData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class StoreUserController
{
    public function __invoke(Request $request): RedirectResponse
    {
        $data = CreateUserData::validateAndCreate($request->all());

        $user = CreateUserAction::execute($data);

        return redirect()
            ->route('admin.users.show', $user)
            ->with('success', 'Користувача успішно створено');
    }
}
