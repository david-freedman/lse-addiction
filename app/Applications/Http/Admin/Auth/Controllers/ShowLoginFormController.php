<?php

namespace App\Applications\Http\Admin\Auth\Controllers;

use Illuminate\View\View;

final class ShowLoginFormController
{
    public function __invoke(): View
    {
        return view('admin.auth.login');
    }
}
