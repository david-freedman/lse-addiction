<?php

namespace App\Applications\Http\Student\Registration\Controllers;

use App\Domains\Student\Actions\RegisterStudentAction;
use App\Domains\Student\Data\RegisterStudentData;
use Illuminate\Http\RedirectResponse;

final class RegisterController
{
    public function __invoke(): RedirectResponse
    {
        $emailVerified = session('email_verified');
        $phoneVerified = session('phone_verified');
        $verifiedEmail = session('verified_email');
        $verifiedPhone = session('verified_phone');

        if (! $emailVerified || ! $phoneVerified) {
            return redirect()->route('student.register')->withErrors([
                'error' => 'Будь ласка, підтвердіть email і телефон перед реєстрацією.',
            ]);
        }

        if (! $verifiedEmail || ! $verifiedPhone) {
            return redirect()->route('student.register')->withErrors([
                'error' => 'Помилка реєстрації. Спробуйте ще раз.',
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
}
