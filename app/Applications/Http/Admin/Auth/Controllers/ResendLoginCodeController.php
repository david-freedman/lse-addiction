<?php

namespace App\Applications\Http\Admin\Auth\Controllers;

use App\Domains\Student\Exceptions\VerificationRateLimitException;
use App\Domains\Verification\Actions\SendVerificationCodeAction;
use App\Models\User;
use Illuminate\Http\RedirectResponse;

final class ResendLoginCodeController
{
    public function __invoke(): RedirectResponse
    {
        $email = session('admin_login_email');

        if (!$email) {
            return redirect()->route('admin.login');
        }

        $user = User::where('email', $email)->first();

        try {
            SendVerificationCodeAction::execute(
                verifiableType: User::class,
                verifiableId: $user->id,
                type: 'email',
                contact: $email,
                purpose: 'login'
            );

            session()->forget('next_resend_at');

            return back()->with('success', 'Код верифікації відправлено повторно.');
        } catch (VerificationRateLimitException $e) {
            $nextResendAt = now()->addSeconds($e->secondsUntilResend)->timestamp;
            session(['next_resend_at' => $nextResendAt]);

            return back()->withErrors(['rate_limit' => $e->getMessage()]);
        }
    }
}
