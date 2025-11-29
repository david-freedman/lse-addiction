<?php

namespace App\Applications\Http\Student\Auth\Controllers;

use App\Domains\Student\Actions\SendVerificationCodeAction;
use App\Domains\Student\Exceptions\VerificationRateLimitException;
use App\Domains\Student\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

final class ResendVerificationCodeController
{
    public function __invoke(): RedirectResponse
    {
        $studentId = session('verification_student_id') ?? Auth::id();

        if (!$studentId) {
            return redirect()->route('student.login');
        }

        $student = Student::find($studentId);

        if (!$student) {
            return redirect()->route('student.login');
        }

        $requirePhone = config('verification.require_phone', true);

        $verificationStep = ($requirePhone && !$student->hasVerifiedPhone())
            ? 'phone'
            : 'email';
        $contact = $verificationStep === 'phone'
            ? $student->phone
            : $student->email;

        try {
            SendVerificationCodeAction::execute(
                type: $verificationStep,
                contact: $contact,
                purpose: 'verification',
                studentId: $student->id
            );

            session(['verification_code_sent' => true]);
            session()->forget('next_resend_at');

            return back()->with('status', __('messages.success.verification_code_sent'));
        } catch (VerificationRateLimitException $e) {
            $nextResendAt = now()->addSeconds($e->secondsUntilResend)->timestamp;
            session(['next_resend_at' => $nextResendAt]);

            return back()->withErrors(['resend' => $e->getMessage()]);
        }
    }
}
