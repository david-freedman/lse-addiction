<?php

namespace App\Applications\Http\Student\Auth\Controllers;

use App\Domains\Student\Actions\AuthenticateStudentAction;
use App\Domains\Student\Actions\VerifyCodeAction;
use App\Domains\Student\Data\VerifyCodeData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class VerifyCompleteVerificationController
{
    public function __invoke(Request $request): RedirectResponse
    {
        $data = VerifyCodeData::validateAndCreate($request->all());

        $student = VerifyCodeAction::execute($data);

        if (! $student) {
            return back()->withErrors(['code' => __('messages.errors.invalid_code')]);
        }

        session()->forget('verification_code_sent');

        if (! $student->isFullyVerified()) {
            return redirect()->route('student.complete-verification');
        }

        AuthenticateStudentAction::execute($student);
        session()->forget('verification_student_id');

        return redirect()->route('student.profile.show');
    }
}
