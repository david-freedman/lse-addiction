<?php

namespace App\Applications\Http\Student\Profile\Controllers;

use App\Domains\Student\Actions\SendVerificationCodeAction;
use App\Domains\Student\Exceptions\VerificationRateLimitException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class ResendChangeCodeController
{
    public function __invoke(Request $request): RedirectResponse
    {
        $type = $request->query('type');
        $contact = $type === 'email' ? session('pending_email') : session('pending_phone');

        if (!$contact || !in_array($type, ['email', 'phone'])) {
            return redirect()->route('student.profile.edit');
        }

        $student = $request->user();
        $purpose = $type === 'email' ? 'change_email' : 'change_phone';

        try {
            SendVerificationCodeAction::execute(
                type: $type,
                contact: $contact,
                purpose: $purpose,
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
