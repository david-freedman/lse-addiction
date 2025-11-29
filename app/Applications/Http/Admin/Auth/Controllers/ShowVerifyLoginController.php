<?php

namespace App\Applications\Http\Admin\Auth\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

final class ShowVerifyLoginController
{
    public function __invoke(): View|RedirectResponse
    {
        $email = session('admin_login_email');

        if (!$email) {
            return redirect()->route('admin.login');
        }

        return view('admin.auth.verify-login', [
            'email' => $email,
            'nextResendAt' => session('next_resend_at'),
        ]);
    }
}
