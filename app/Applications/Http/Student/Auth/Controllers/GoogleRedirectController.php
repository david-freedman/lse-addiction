<?php

namespace App\Applications\Http\Student\Auth\Controllers;

use Illuminate\Http\RedirectResponse;
use Laravel\Socialite\Facades\Socialite;

final class GoogleRedirectController
{
    public function __invoke(): RedirectResponse
    {
        return Socialite::driver('google')
            ->redirectUrl(route('student.auth.google.callback'))
            ->redirect();
    }
}
