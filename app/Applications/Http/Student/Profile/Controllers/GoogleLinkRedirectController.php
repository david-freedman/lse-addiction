<?php

namespace App\Applications\Http\Student\Profile\Controllers;

use Illuminate\Http\RedirectResponse;
use Laravel\Socialite\Facades\Socialite;

final class GoogleLinkRedirectController
{
    public function __invoke(): RedirectResponse
    {
        return Socialite::driver('google')
            ->redirectUrl(route('student.profile.google.callback'))
            ->redirect();
    }
}
