<?php

namespace App\Applications\Http\Admin\Controllers;

use App\Domains\Student\Data\VerifyCodeData;
use App\Domains\Student\Exceptions\VerificationRateLimitException;
use App\Domains\Verification\Actions\SendVerificationCodeAction;
use App\Domains\Verification\Actions\VerifyCodeAction;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AdminAuthController
{
    public function showLoginForm(): View
    {
        return view('admin.auth.login');
    }

    public function sendLoginCode(Request $request): RedirectResponse
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)
            ->whereNotNull('role')
            ->first();

        if (! $user) {
            return back()->withErrors(['email' => 'Користувача з такою адресою не знайдено або немає прав доступу.']);
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

    public function showVerifyLogin(): View|RedirectResponse
    {
        $email = session('admin_login_email');

        if (! $email) {
            return redirect()->route('admin.login');
        }

        return view('admin.auth.verify-login', [
            'email' => $email,
            'nextResendAt' => session('next_resend_at'),
        ]);
    }

    public function verifyLogin(Request $request): RedirectResponse
    {
        $email = session('admin_login_email');

        if (! $email) {
            return redirect()->route('admin.login');
        }

        $request->validate(['code' => 'required|string']);

        $verification = \App\Domains\Verification\Models\Verification::query()
            ->where('contact', $email)
            ->where('type', 'email')
            ->pending()
            ->notExpired()
            ->first();

        if ($verification && $verification->isLocked()) {
            return back()->withErrors(['code' => 'Код заблоковано після занадто багатьох невдалих спроб. Будь ласка, запросіть новий код.']);
        }

        $data = VerifyCodeData::from([
            'contact' => $email,
            'code' => $request->code,
            'type' => 'email',
        ]);

        $user = VerifyCodeAction::execute($data);

        if (! $user || ! ($user instanceof User)) {
            $remaining = $verification?->getRemainingAttempts() ?? 0;
            $message = $remaining > 0
                ? "Невірний код верифікації. Залишилось спроб: {$remaining}"
                : 'Невірний код верифікації.';

            return back()->withErrors(['code' => $message]);
        }

        Auth::guard('admin')->login($user, $request->boolean('remember'));

        session()->forget(['admin_login_email', 'next_resend_at']);

        return redirect()->route('admin.dashboard');
    }

    public function resendCode(): RedirectResponse
    {
        $email = session('admin_login_email');

        if (! $email) {
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

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
