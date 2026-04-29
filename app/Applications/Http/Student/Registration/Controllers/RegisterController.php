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
        [$email, $verifiedPhone] = $this->resolveVerifiedContacts($request);

        if (! $email || ! $verifiedPhone) {
            return redirect()->route('student.register')->withErrors([
                'error' => 'Будь ласка, підтвердіть телефон перед реєстрацією.',
            ]);
        }

        $data = RegisterStudentData::validateAndCreate([
            'email' => $email,
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
        $email = $request->input('email');
        $phoneToken = $request->input('phone_token');

        $verifiedPhone = null;

        if ($phoneToken) {
            try {
                $phoneData = decrypt($phoneToken);

                if (
                    isset($phoneData['type'], $phoneData['contact'], $phoneData['expires_at']) &&
                    $phoneData['type'] === 'phone' &&
                    now()->timestamp <= $phoneData['expires_at']
                ) {
                    $verifiedPhone = $phoneData['contact'];
                }
            } catch (\Exception) {
                // Fall through to session fallback
            }
        }

        if (! $verifiedPhone) {
            $phoneVerified = session('phone_verified');
            $sessionPhone = session('verified_phone');

            if ($phoneVerified && $sessionPhone) {
                $verifiedPhone = $sessionPhone;
            }
        }

        return [$email ?: null, $verifiedPhone];
    }
}
