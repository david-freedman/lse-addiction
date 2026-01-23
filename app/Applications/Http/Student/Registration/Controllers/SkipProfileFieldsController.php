<?php

namespace App\Applications\Http\Student\Registration\Controllers;

use App\Domains\Student\Actions\AuthenticateStudentAction;
use App\Domains\Student\Actions\StoreStudentConsentsAction;
use App\Domains\Student\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class SkipProfileFieldsController
{
    public function __invoke(Request $request): RedirectResponse
    {
        $studentId = session('student_id');

        if (!$studentId) {
            return redirect()->route('student.register');
        }

        $student = Student::find($studentId);

        if (!$student) {
            return redirect()->route('student.register');
        }

        $request->validate([
            'consent_privacy_policy' => ['required', 'accepted'],
            'consent_public_offer' => ['required', 'accepted'],
        ], [
            'consent_privacy_policy.required' => 'Необхідно погодитись з Політикою обробки персональних даних',
            'consent_privacy_policy.accepted' => 'Необхідно погодитись з Політикою обробки персональних даних',
            'consent_public_offer.required' => 'Необхідно погодитись з Умовами публічної оферти',
            'consent_public_offer.accepted' => 'Необхідно погодитись з Умовами публічної оферти',
        ]);

        StoreStudentConsentsAction::execute($student, $request->ip());

        AuthenticateStudentAction::execute($student);

        session()->forget(['student_id', 'student_email', 'student_phone', 'phone_code_expires_at', 'email_code_expires_at']);

        return redirect()->route('student.dashboard')->with('info', __('messages.profile_fields.skipped'));
    }
}
