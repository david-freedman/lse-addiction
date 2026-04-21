<?php

namespace App\Applications\Http\Student\Auth\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

final class ShowLoginFormController
{
    public function __invoke(Request $request): View
    {
        if ($request->has('course') && preg_match('/^[cw]\d+$/', $request->query('course'))) {
            session(['pending_course_id' => $request->query('course')]);
        }

        return view('student.auth.login');
    }
}
