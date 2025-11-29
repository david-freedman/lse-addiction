<?php

namespace App\Applications\Http\Admin\Auth\Controllers;

use App\Domains\Student\Data\VerifyCodeData;
use App\Domains\Verification\Actions\VerifyCodeAction;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

final class VerifyLoginController
{
    public function __invoke(Request $request): RedirectResponse
    {
        $email = session('admin_login_email');

        if (!$email) {
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

        if (!$user || !($user instanceof User)) {
            $remaining = $verification?->getRemainingAttempts() ?? 0;
            $message = $remaining > 0
                ? "Невірний код верифікації. Залишилось спроб: {$remaining}"
                : 'Невірний код верифікації.';

            return back()->withErrors(['code' => $message]);
        }

        Auth::guard('admin')->login($user);

        session()->forget(['admin_login_email', 'next_resend_at']);

        return redirect()->route('admin.dashboard');
    }
}
