<?php

namespace App\Applications\Http\Student\Profile\Controllers;

use App\Domains\Student\Actions\SaveStudentProfileFieldValuesAction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class UpdateProfileFieldsController
{
    public function __invoke(Request $request): RedirectResponse
    {
        $student = $request->user();
        $profileFieldsData = $request->input('profile_fields', []);

        try {
            SaveStudentProfileFieldValuesAction::execute($student, $profileFieldsData);

            return redirect()->route('student.profile.show')
                ->with('success', 'Анкетні дані успішно оновлено');
        } catch (\Exception $e) {
            return back()->withErrors(['profile_fields' => $e->getMessage()])->withInput();
        }
    }
}
