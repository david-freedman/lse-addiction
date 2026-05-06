<?php

namespace App\Applications\Http\Student\Auth\Controllers;

use App\Domains\Student\Actions\AuthenticateStudentAction;
use App\Domains\Student\Actions\EnrollFromSessionAction;
use App\Domains\Student\Actions\FindOrCreateStudentViaGoogleAction;
use Illuminate\Http\RedirectResponse;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

final class GoogleCallbackController
{
    public function __invoke(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')
                ->redirectUrl(route('student.auth.google.callback'))
                ->user();
        } catch (Throwable) {
            return redirect()->route('student.login')
                ->withErrors(['google' => 'Не вдалося авторизуватися через Google. Спробуйте ще раз.']);
        }

        $student = FindOrCreateStudentViaGoogleAction::execute($googleUser);

        if (! $student->hasContactDetails()) {
            session(['student_id' => $student->id]);

            return redirect()->route('student.contact-details.show');
        }

        AuthenticateStudentAction::execute($student);
        EnrollFromSessionAction::execute($student);

        return redirect()->route('student.dashboard');
    }
}
