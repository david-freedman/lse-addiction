<?php

namespace App\Applications\Http\Student\Profile\Controllers;

use App\Domains\Student\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

final class GoogleUnlinkController
{
    public function __invoke(): RedirectResponse
    {
        /** @var Student $student */
        $student = Auth::user();

        if ($student->phone === null) {
            return redirect()->route('student.profile.show')
                ->withErrors(['google' => 'Неможливо відв\'язати Google: спочатку додайте номер телефону для входу.']);
        }

        $student->update(['google_id' => null]);

        return redirect()->route('student.profile.show')
            ->with('success', 'Google акаунт успішно відв\'язано.');
    }
}
