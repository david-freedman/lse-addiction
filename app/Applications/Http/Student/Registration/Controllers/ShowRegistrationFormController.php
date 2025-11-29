<?php

namespace App\Applications\Http\Student\Registration\Controllers;

use Illuminate\View\View;

final class ShowRegistrationFormController
{
    public function __invoke(): View
    {
        return view('student.auth.register');
    }
}
