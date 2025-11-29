<?php

namespace App\Applications\Http\Student\Profile\Controllers;

use App\Domains\Student\Actions\GetActiveProfileFieldsAction;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class EditProfileController
{
    public function __invoke(Request $request): View
    {
        $student = $request->user();
        $profileFields = GetActiveProfileFieldsAction::execute();

        $existingValues = $student->profileFieldValues()
            ->with('profileField')
            ->get()
            ->keyBy('profile_field_id')
            ->map(fn ($item) => $item->value);

        return view('student.profile.edit', compact('student', 'profileFields', 'existingValues'));
    }
}
