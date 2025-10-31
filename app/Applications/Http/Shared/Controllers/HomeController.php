<?php

namespace App\Applications\Http\Shared\Controllers;

use Illuminate\View\View;

class HomeController
{
    public function index(): View
    {
        return view('home');
    }
}
