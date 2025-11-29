<?php

namespace App\Applications\Http\Admin\User\Controllers;

use App\Domains\User\Actions\ToggleUserStatusAction;
use App\Models\User;
use Illuminate\Http\RedirectResponse;

final class ToggleUserStatusController
{
    public function __invoke(User $user): RedirectResponse
    {
        $user = ToggleUserStatusAction::execute($user);

        $message = $user->is_active
            ? 'Користувача активовано'
            : 'Користувача деактивовано';

        return redirect()
            ->back()
            ->with('success', $message);
    }
}
