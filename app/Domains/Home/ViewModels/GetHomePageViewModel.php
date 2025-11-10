<?php

namespace App\Domains\Home\ViewModels;

use App\Domains\Course\Models\Course;
use App\Domains\Faq\Models\Faq;
use App\Models\User;

class GetHomePageViewModel
{
    public function courses()
    {
        return Course::take(3)->get();
    }

    public function coaches()
    {
        return User::coaches()->get();
    }

    public function faqs()
    {
        return Faq::active()->ordered()->get();
    }
}
