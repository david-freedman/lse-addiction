<?php

namespace App\Applications\Http\Student\Registration\Controllers;

use App\Domains\Student\Actions\AuthenticateStudentAction;
use App\Domains\Student\Models\Student;
use Illuminate\Http\RedirectResponse;

final class SkipProfileFieldsController
{
    public function __invoke(): RedirectResponse
    {
        $studentId = session('student_id');

        if (!$studentId) {
            return redirect()->route('student.register');
        }

        $student = Student::find($studentId);

        if (!$student) {
            return redirect()->route('student.register');
        }

        AuthenticateStudentAction::execute($student);

        session()->forget(['student_id', 'student_email', 'student_phone', 'phone_code_expires_at', 'email_code_expires_at']);

        return redirect()->route('student.profile.show')->with('info', __('messages.profile_fields.skipped'));
    }
}
