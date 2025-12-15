<?php

namespace App\Applications\Http\Student\Profile\Controllers;

use App\Domains\Student\Actions\DeleteStudentSelfAction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

final class DeleteAccountController
{
    public function __invoke(Request $request): RedirectResponse
    {
        $student = $request->user();

        DeleteStudentSelfAction::execute($student);

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('student.login')
            ->with('success', __('messages.account.deleted'));
    }
}
