<?php

namespace App\Applications\Http\Admin\Auth\Controllers;

use App\Domains\Student\Exceptions\VerificationRateLimitException;
use App\Domains\Verification\Actions\SendVerificationCodeAction;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class SendLoginCodeController
{
    public function __invoke(Request $request): RedirectResponse
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)
            ->whereNotNull('role')
            ->where('is_active', true)
            ->first();

        if (! $user) {
            return back()->withErrors(['email' => 'Користувача з такою адресою не знайдено, немає прав доступу або обліковий запис деактивовано.']);
        }

        try {
            SendVerificationCodeAction::execute(
                verifiableType: User::class,
                verifiableId: $user->id,
                type: 'email',
                contact: $request->email,
                purpose: 'login'
            );

            session(['admin_login_email' => $request->email]);
            session()->forget('next_resend_at');

            return redirect()->route('admin.verify-login.show');
        } catch (VerificationRateLimitException $e) {
            $nextResendAt = now()->addSeconds($e->secondsUntilResend)->timestamp;
            session(['next_resend_at' => $nextResendAt]);

            return back()->withErrors(['rate_limit' => $e->getMessage()]);
        }
    }
}
