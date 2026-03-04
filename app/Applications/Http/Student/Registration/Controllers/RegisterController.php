<?php

namespace App\Applications\Http\Student\Registration\Controllers;

use App\Domains\Student\Actions\RegisterStudentAction;
use App\Domains\Student\Data\RegisterStudentData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class RegisterController
{
    public function __invoke(Request $request): RedirectResponse
    {
        [$verifiedEmail, $verifiedPhone] = $this->resolveVerifiedContacts($request);

        if (! $verifiedEmail || ! $verifiedPhone) {
            return redirect()->route('student.register')->withErrors([
                'error' => 'Будь ласка, підтвердіть email і телефон перед реєстрацією.',
            ]);
        }

        $data = RegisterStudentData::validateAndCreate([
            'email' => $verifiedEmail,
            'phone' => $verifiedPhone,
        ]);

        $student = RegisterStudentAction::execute($data);

        $student->markEmailAsVerified();
        $student->markPhoneAsVerified();

        session([
            'student_id' => $student->id,
            'student_email' => $student->email->value,
            'student_phone' => $student->phone->value,
        ]);

        session()->forget([
            'email_verified',
            'phone_verified',
            'verified_email',
            'verified_phone',
            'registration_email',
            'registration_phone',
            'email_code_expires_at',
            'phone_code_expires_at',
        ]);

        return redirect()->route('student.contact-details.show');
    }

    private function resolveVerifiedContacts(Request $request): array
    {
        // Try token-based verification first (more reliable than session)
        $emailToken = $request->input('email_token');
        $phoneToken = $request->input('phone_token');

        if ($emailToken && $phoneToken) {
            try {
                $emailData = decrypt($emailToken);
                $phoneData = decrypt($phoneToken);

                if (
                    isset($emailData['type'], $emailData['contact'], $emailData['expires_at']) &&
                    isset($phoneData['type'], $phoneData['contact'], $phoneData['expires_at']) &&
                    $emailData['type'] === 'email' &&
                    $phoneData['type'] === 'phone' &&
                    now()->timestamp <= $emailData['expires_at'] &&
                    now()->timestamp <= $phoneData['expires_at']
                ) {
                    return [$emailData['contact'], $phoneData['contact']];
                }
            } catch (\Exception) {
                // Fall through to session fallback
            }
        }

        // Fallback: session-based (for backward compat / local env)
        $emailVerified = session('email_verified');
        $phoneVerified = session('phone_verified');
        $verifiedEmail = session('verified_email');
        $verifiedPhone = session('verified_phone');

        if ($emailVerified && $phoneVerified && $verifiedEmail && $verifiedPhone) {
            return [$verifiedEmail, $verifiedPhone];
        }

        return [null, null];
    }
}
