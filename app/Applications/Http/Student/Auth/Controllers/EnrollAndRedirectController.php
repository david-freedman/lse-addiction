<?php

namespace App\Applications\Http\Student\Auth\Controllers;

use App\Domains\Student\Actions\EnrollFromSessionAction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

final class EnrollAndRedirectController
{
    public function __invoke(Request $request): RedirectResponse
    {
        if ($request->has('course') && preg_match('/^[cw]\d+$/', $request->query('course'))) {
            session(['pending_course_id' => $request->query('course')]);
        }

        $student = Auth::user();

        if ($student) {
            EnrollFromSessionAction::execute($student);
            return redirect()->route('student.dashboard');
        }

        return redirect()->route('student.login');
    }
}
