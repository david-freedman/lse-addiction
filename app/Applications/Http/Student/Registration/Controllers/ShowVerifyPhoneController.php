<?php

namespace App\Applications\Http\Student\Registration\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

final class ShowVerifyPhoneController
{
    public function __invoke(): View|RedirectResponse
    {
        $studentId = session('student_id');

        if (!$studentId) {
            return redirect()->route('student.register');
        }

        return view('student.auth.verify-phone');
    }
}
