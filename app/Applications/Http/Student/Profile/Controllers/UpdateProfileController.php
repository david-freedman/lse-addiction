<?php

namespace App\Applications\Http\Student\Profile\Controllers;

use App\Domains\Student\Actions\SendVerificationCodeAction;
use App\Domains\Student\Actions\UpdateStudentPersonalDetailsAction;
use App\Domains\Student\Actions\UploadStudentProfilePhotoAction;
use App\Domains\Student\Data\UpdateContactData;
use App\Domains\Student\Data\UpdatePersonalDetailsData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class UpdateProfileController
{
    public function __invoke(Request $request): RedirectResponse
    {
        $student = $request->user();
        $hasPersonalDetailsChanges = false;
        $hasPhotoChanges = false;

        if ($request->hasFile('profile_photo')) {
            $request->validate([
                'profile_photo' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            ]);

            try {
                UploadStudentProfilePhotoAction::execute($student, $request->file('profile_photo'));
                $hasPhotoChanges = true;
            } catch (\Exception $e) {
                return back()->withErrors(['profile_photo' => $e->getMessage()])->withInput();
            }
        }

        if ($request->has(['surname', 'name', 'birthday', 'city'])) {
            try {
                $personalData = UpdatePersonalDetailsData::validateAndCreate($request->only(['surname', 'name', 'birthday', 'city']));

                UpdateStudentPersonalDetailsAction::execute($student, $personalData);
                $hasPersonalDetailsChanges = true;
            } catch (\Exception $e) {
                return back()->withErrors(['personal_details' => $e->getMessage()])->withInput();
            }
        }

        $inputEmail = $request->input('email');
        $inputPhone = $request->input('phone');

        $emailChanged = $inputEmail && $inputEmail !== (string) $student->email;
        $phoneChanged = $inputPhone && $inputPhone !== (string) $student->phone;

        if ($emailChanged || $phoneChanged) {
            $contactData = UpdateContactData::validateAndCreate([
                'email' => $emailChanged ? $inputEmail : null,
                'phone' => $phoneChanged ? $inputPhone : null,
            ]);

            if ($contactData->email) {
                SendVerificationCodeAction::execute(
                    type: 'email',
                    contact: $contactData->email,
                    purpose: 'change_email',
                    studentId: $student->id
                );

                session(['pending_email' => $contactData->email]);

                return redirect()->route('student.verify-change.show', ['type' => 'email'])
                    ->with('success', $hasPersonalDetailsChanges ? __('messages.profile.personal_details_updated_verify_contact') : null);
            }

            if ($contactData->phone) {
                SendVerificationCodeAction::execute(
                    type: 'phone',
                    contact: $contactData->phone,
                    purpose: 'change_phone',
                    studentId: $student->id
                );

                session(['pending_phone' => $contactData->phone]);

                return redirect()->route('student.verify-change.show', ['type' => 'phone'])
                    ->with('success', $hasPersonalDetailsChanges ? __('messages.profile.personal_details_updated_verify_contact') : null);
            }
        }

        if ($hasPersonalDetailsChanges || $hasPhotoChanges) {
            return redirect()->route('student.profile.show')
                ->with('success', __('messages.profile.personal_details_updated'));
        }

        return back()->withErrors(['error' => __('messages.profile.no_changes')]);
    }
}
