<?php

namespace App\Applications\Http\Student\Registration\Controllers;

use App\Domains\Student\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

final class ShowContactDetailsController
{
    public function __invoke(): View|RedirectResponse
    {
        $studentId = session('student_id');

        if (! $studentId) {
            return redirect()->route('student.register');
        }

        $student = Student::find($studentId);

        if (! $student || ! $student->isFullyVerified()) {
            return redirect()->route('student.register');
        }

        return view('student.auth.contact-details');
    }
}
