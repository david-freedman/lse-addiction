<?php

namespace App\Applications\Http\Student\Auth\Controllers;

use App\Domains\Student\Actions\AuthenticateStudentAction;
use App\Domains\Student\Actions\EnrollFromSessionAction;
use App\Domains\Student\Actions\VerifyCodeAction;
use App\Domains\Student\Data\VerifyCodeData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class VerifyLoginController
{
    public function __invoke(Request $request): RedirectResponse
    {
        $data = VerifyCodeData::validateAndCreate($request->all());

        $student = VerifyCodeAction::execute($data);

        if (! $student) {
            return back()->withErrors(['code' => __('messages.errors.invalid_code')]);
        }

        if (! $student->isFullyVerified()) {
            session()->forget(['login_contact', 'login_type']);
            session(['verification_student_id' => $student->id]);

            return redirect()->route('student.complete-verification');
        }

        if (! $student->hasContactDetails()) {
            session()->forget(['login_contact', 'login_type']);
            session(['student_id' => $student->id]);

            return redirect()->route('student.contact-details.show');
        }

        AuthenticateStudentAction::execute($student);
        EnrollFromSessionAction::execute($student);

        session()->forget(['login_contact', 'login_type']);

        return redirect()->route('student.dashboard');
    }
}
