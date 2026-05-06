<?php

namespace App\Applications\Http\Student\Profile\Controllers;

use App\Domains\Student\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

final class GoogleLinkCallbackController
{
    public function __invoke(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')
                ->redirectUrl(route('student.profile.google.callback'))
                ->user();
        } catch (Throwable) {
            return redirect()->route('student.profile.show')
                ->withErrors(['google' => 'Не вдалося підключитися до Google. Спробуйте ще раз.']);
        }

        /** @var Student $student */
        $student = Auth::user();

        $existing = Student::where('google_id', $googleUser->getId())
            ->where('id', '!=', $student->id)
            ->exists();

        if ($existing) {
            return redirect()->route('student.profile.show')
                ->withErrors(['google' => 'Цей Google акаунт вже прив\'язаний до іншого облікового запису.']);
        }

        $student->update(['google_id' => $googleUser->getId()]);

        return redirect()->route('student.profile.show')
            ->with('success', 'Google акаунт успішно прив\'язано.');
    }
}
