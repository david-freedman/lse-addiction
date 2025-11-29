<?php

namespace App\Applications\Http\Student\Auth\Controllers;

use App\Domains\Student\Actions\SendVerificationCodeAction;
use App\Domains\Student\Exceptions\VerificationRateLimitException;
use App\Domains\Student\Models\Student;
use Illuminate\Http\RedirectResponse;

final class ResendLoginCodeController
{
    public function __invoke(): RedirectResponse
    {
        $contact = session('login_contact');
        $type = session('login_type');

        if (!$contact || !$type) {
            return redirect()->route('student.login');
        }

        $student = Student::query()
            ->where($type === 'email' ? 'email' : 'phone', $contact)
            ->first();

        if (!$student) {
            return redirect()->route('student.login');
        }

        try {
            SendVerificationCodeAction::execute(
                type: $type,
                contact: $contact,
                purpose: 'login',
                studentId: $student->id
            );

            session()->forget('next_resend_at');

            return back()->with('status', 'Код відправлено повторно');
        } catch (VerificationRateLimitException $e) {
            $nextResendAt = now()->addSeconds($e->secondsUntilResend)->timestamp;
            session(['next_resend_at' => $nextResendAt]);

            return back()->withErrors(['resend' => $e->getMessage()]);
        }
    }
}
