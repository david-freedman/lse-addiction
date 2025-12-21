<?php

namespace App\Applications\Http\Student\Registration\Controllers;

use App\Domains\Student\Actions\AuthenticateStudentAction;
use App\Domains\Student\Actions\SaveStudentProfileFieldValuesAction;
use App\Domains\Student\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class SaveProfileFieldsController
{
    public function __invoke(Request $request): RedirectResponse
    {
        $studentId = session('student_id');

        if (! $studentId) {
            return redirect()->route('student.register');
        }

        $student = Student::find($studentId);

        if (! $student || ! $student->isFullyVerified() || ! $student->hasContactDetails()) {
            return redirect()->route('student.register');
        }

        $fieldValues = $request->input('profile_fields', []);

        SaveStudentProfileFieldValuesAction::execute($student, $fieldValues);

        AuthenticateStudentAction::execute($student);

        session()->forget(['student_id', 'student_email', 'student_phone', 'phone_code_expires_at', 'email_code_expires_at']);

        return redirect()->route('student.dashboard')->with('success', __('messages.profile_fields.saved'));
    }
}
