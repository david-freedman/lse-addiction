<?php

namespace App\Applications\Http\Student\Registration\Controllers;

use App\Domains\Student\Actions\SendVerificationCodeAction;
use App\Domains\Student\Exceptions\VerificationRateLimitException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class ResendCodeController
{
    public function __invoke(Request $request): RedirectResponse
    {
        $request->validate([
            'type' => ['required', 'in:email,phone'],
        ]);

        $type = $request->input('type');
        $studentId = session('student_id');

        if (!$studentId) {
            return redirect()->route('student.register');
        }

        $contact = $type === 'email' ? session('student_email') : session('student_phone');

        if (!$contact) {
            return back()->withErrors(['type' => __('messages.errors.contact_not_found')]);
        }

        try {
            SendVerificationCodeAction::execute(
                type: $type,
                contact: $contact,
                purpose: 'registration',
                studentId: $studentId
            );

            $expiresAt = now()->addMinutes(15)->timestamp;
            $sessionKey = $type === 'email' ? 'email_code_expires_at' : 'phone_code_expires_at';
            session([$sessionKey => $expiresAt]);
            session()->forget('next_resend_at');

            $contactType = $type === 'email' ? __('messages.verification.code_resent_email') : __('messages.verification.code_resent_phone');

            return back()->with('success', __('messages.verification.code_resent', ['type' => $contactType]));
        } catch (VerificationRateLimitException $e) {
            $nextResendAt = now()->addSeconds($e->secondsUntilResend)->timestamp;
            session(['next_resend_at' => $nextResendAt]);

            return back()->withErrors(['resend' => $e->getMessage()]);
        }
    }
}
