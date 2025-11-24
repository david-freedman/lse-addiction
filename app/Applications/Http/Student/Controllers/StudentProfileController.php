<?php

namespace App\Applications\Http\Student\Controllers;

use App\Domains\Student\Actions\GetActiveProfileFieldsAction;
use App\Domains\Student\Actions\SaveStudentProfileFieldValuesAction;
use App\Domains\Student\Actions\SendVerificationCodeAction;
use App\Domains\Student\Actions\UpdateStudentContactAction;
use App\Domains\Student\Actions\UpdateStudentPersonalDetailsAction;
use App\Domains\Student\Actions\UploadStudentProfilePhotoAction;
use App\Domains\Student\Data\UpdateContactData;
use App\Domains\Student\Data\UpdatePersonalDetailsData;
use App\Domains\Student\Data\VerifyCodeData;
use App\Domains\Student\Exceptions\VerificationRateLimitException;
use App\Domains\Student\ViewModels\StudentProfileViewModel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentProfileController
{
    public function show(Request $request): View
    {
        $viewModel = new StudentProfileViewModel($request->user());

        return view('student.profile.show', compact('viewModel'));
    }

    public function edit(Request $request): View
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

    public function update(Request $request): RedirectResponse
    {
        $student = $request->user();
        $hasPersonalDetailsChanges = false;
        $hasContactChanges = false;
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
                $hasContactChanges = true;

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
                $hasContactChanges = true;

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

    public function showVerifyChange(Request $request): View
    {
        $type = $request->query('type');
        $contact = $type === 'email' ? session('pending_email') : session('pending_phone');

        if (! $contact || ! in_array($type, ['email', 'phone'])) {
            return redirect()->route('student.profile.show');
        }

        return view('student.profile.verify-change', compact('type', 'contact'));
    }

    public function verifyChange(Request $request): RedirectResponse
    {
        $verifyData = VerifyCodeData::validateAndCreate($request->all());
        $originalStudent = $request->user();

        $updateData = new UpdateContactData(
            email: $verifyData->type === 'email' ? session('pending_email') : null,
            phone: $verifyData->type === 'phone' ? session('pending_phone') : null,
        );

        $updatedStudent = UpdateStudentContactAction::execute(
            student: $originalStudent,
            data: $updateData,
            verifyData: $verifyData
        );

        $contactChanged = ($verifyData->type === 'email' && (string) $updatedStudent->email !== (string) $originalStudent->email)
            || ($verifyData->type === 'phone' && (string) $updatedStudent->phone !== (string) $originalStudent->phone);

        if (! $contactChanged) {
            return back()->withErrors(['code' => 'Невірний код підтвердження або код застарів. Спробуйте ще раз.']);
        }

        session()->forget(['pending_email', 'pending_phone']);

        return redirect()->route('student.profile.show')
            ->with('success', __('messages.profile.contact_updated'));
    }

    public function updateProfileFields(Request $request): RedirectResponse
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

    public function resendChangeCode(Request $request): RedirectResponse
    {
        $type = $request->query('type');
        $contact = $type === 'email' ? session('pending_email') : session('pending_phone');

        if (! $contact || ! in_array($type, ['email', 'phone'])) {
            return redirect()->route('student.profile.edit');
        }

        $student = $request->user();
        $purpose = $type === 'email' ? 'change_email' : 'change_phone';

        try {
            SendVerificationCodeAction::execute(
                type: $type,
                contact: $contact,
                purpose: $purpose,
                studentId: $student->id
            );

            session()->forget('next_resend_at');

            return back()->with('status', 'Код відправлено повторно');
        } catch (VerificationRateLimitException $e) {
            $nextResendAt = now()->addSeconds($e->secondsUntilResend)->timestamp;
            session(['next_resend_at' => $nextResendAt]);

            return back()->withErrors(['resend' => $e->getMessage()]);
        }
    }
}
