<?php

namespace App\Applications\Http\Student\Profile\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class ShowVerifyChangeController
{
    public function __invoke(Request $request): View|RedirectResponse
    {
        $type = $request->query('type');
        $contact = $type === 'email' ? session('pending_email') : session('pending_phone');

        if (! $contact || ! in_array($type, ['email', 'phone'])) {
            return redirect()->route('student.profile.show');
        }

        return view('student.profile.verify-change', compact('type', 'contact'));
    }
}
