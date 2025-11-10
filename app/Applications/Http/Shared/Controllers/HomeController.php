<?php

namespace App\Applications\Http\Shared\Controllers;

use App\Domains\Course\Models\Course;
use App\Domains\Faq\Models\Faq;
use App\Models\User;
use Illuminate\View\View;

class HomeController
{
    public function index(): View
    {
        return view('home', [
            'courses' => Course::take(3)->get(),
            'coaches' => User::coaches()->get(),
            'faqs' => Faq::active()->ordered()->get(),
        ]);
    }
}
