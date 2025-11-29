<?php

namespace App\Applications\Http\Student\Auth\Controllers;

use Illuminate\View\View;

final class ShowLoginFormController
{
    public function __invoke(): View
    {
        return view('student.auth.login');
    }
}
