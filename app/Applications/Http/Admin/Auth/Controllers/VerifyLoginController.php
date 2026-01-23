<?php

namespace App\Applications\Http\Admin\Auth\Controllers;

use App\Domains\ActivityLog\Actions\LogActivityAction;
use App\Domains\ActivityLog\Data\ActivityLogData;
use App\Domains\ActivityLog\Enums\ActivitySubject;
use App\Domains\ActivityLog\Enums\ActivityType;
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

            LogActivityAction::execute(ActivityLogData::from([
                'subject_type' => ActivitySubject::Admin,
                'subject_id' => null,
                'activity_type' => ActivityType::AdminLoginFailed,
                'description' => 'Admin login failed',
                'properties' => [
                    'email' => $email,
                    'reason' => 'invalid_code',
                    'remaining_attempts' => $remaining,
                ],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]));

            return back()->withErrors(['code' => $message]);
        }

        Auth::guard('admin')->login($user);

        LogActivityAction::execute(ActivityLogData::from([
            'subject_type' => ActivitySubject::Admin,
            'subject_id' => $user->id,
            'activity_type' => ActivityType::AdminLoginSuccess,
            'description' => 'Admin logged in',
            'properties' => [
                'user_id' => $user->id,
                'email' => $user->email,
                'name' => $user->name,
            ],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]));

        session()->forget(['admin_login_email', 'next_resend_at']);

        return redirect()->route('admin.dashboard');
    }
}
