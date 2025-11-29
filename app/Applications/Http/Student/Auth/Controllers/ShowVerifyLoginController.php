<?php

namespace App\Applications\Http\Student\Auth\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

final class ShowVerifyLoginController
{
    public function __invoke(): View|RedirectResponse
    {
        $contact = session('login_contact');
        $type = session('login_type');

        if (!$contact || !$type) {
            return redirect()->route('student.login');
        }

        return view('student.auth.verify-login', compact('contact', 'type'));
    }
}
