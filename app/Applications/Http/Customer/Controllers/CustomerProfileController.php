<?php

namespace App\Applications\Http\Customer\Controllers;

use App\Domains\Customer\Actions\GetActiveProfileFieldsAction;
use App\Domains\Customer\Actions\SaveCustomerProfileFieldValuesAction;
use App\Domains\Customer\Actions\SendVerificationCodeAction;
use App\Domains\Customer\Actions\UpdateCustomerContactAction;
use App\Domains\Customer\Actions\UpdateCustomerPersonalDetailsAction;
use App\Domains\Customer\Actions\UploadCustomerProfilePhotoAction;
use App\Domains\Customer\Data\UpdateContactData;
use App\Domains\Customer\Data\UpdatePersonalDetailsData;
use App\Domains\Customer\Data\VerifyCodeData;
use App\Domains\Customer\Exceptions\VerificationRateLimitException;
use App\Domains\Customer\ViewModels\CustomerProfileViewModel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerProfileController
{
    public function show(Request $request): View
    {
        $viewModel = new CustomerProfileViewModel($request->user());

        return view('customer.profile.show', compact('viewModel'));
    }

    public function edit(Request $request): View
    {
        $customer = $request->user();
        $profileFields = GetActiveProfileFieldsAction::execute();

        $existingValues = $customer->profileFieldValues()
            ->with('profileField')
            ->get()
            ->keyBy('profile_field_id')
            ->map(fn($item) => $item->value);

        return view('customer.profile.edit', compact('customer', 'profileFields', 'existingValues'));
    }

    public function update(Request $request): RedirectResponse
    {
        $customer = $request->user();
        $hasPersonalDetailsChanges = false;
        $hasContactChanges = false;
        $hasPhotoChanges = false;

        if ($request->hasFile('profile_photo')) {
            $request->validate([
                'profile_photo' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            ]);

            try {
                UploadCustomerProfilePhotoAction::execute($customer, $request->file('profile_photo'));
                $hasPhotoChanges = true;
            } catch (\Exception $e) {
                return back()->withErrors(['profile_photo' => $e->getMessage()])->withInput();
            }
        }

        if ($request->has(['surname', 'name', 'birthday', 'city'])) {
            try {
                $personalData = UpdatePersonalDetailsData::validateAndCreate($request->only(['surname', 'name', 'birthday', 'city']));

                UpdateCustomerPersonalDetailsAction::execute($customer, $personalData);
                $hasPersonalDetailsChanges = true;
            } catch (\Exception $e) {
                return back()->withErrors(['personal_details' => $e->getMessage()])->withInput();
            }
        }

        $inputEmail = $request->input('email');
        $inputPhone = $request->input('phone');

        $emailChanged = $inputEmail && $inputEmail !== (string) $customer->email;
        $phoneChanged = $inputPhone && $inputPhone !== (string) $customer->phone;

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
                    customerId: $customer->id
                );

                session(['pending_email' => $contactData->email]);
                $hasContactChanges = true;

                return redirect()->route('customer.verify-change.show', ['type' => 'email'])
                    ->with('success', $hasPersonalDetailsChanges ? __('messages.profile.personal_details_updated_verify_contact') : null);
            }

            if ($contactData->phone) {
                SendVerificationCodeAction::execute(
                    type: 'phone',
                    contact: $contactData->phone,
                    purpose: 'change_phone',
                    customerId: $customer->id
                );

                session(['pending_phone' => $contactData->phone]);
                $hasContactChanges = true;

                return redirect()->route('customer.verify-change.show', ['type' => 'phone'])
                    ->with('success', $hasPersonalDetailsChanges ? __('messages.profile.personal_details_updated_verify_contact') : null);
            }
        }

        if ($hasPersonalDetailsChanges || $hasPhotoChanges) {
            return redirect()->route('customer.profile.show')
                ->with('success', __('messages.profile.personal_details_updated'));
        }

        return back()->withErrors(['error' => __('messages.profile.no_changes')]);
    }

    public function showVerifyChange(Request $request): View
    {
        $type = $request->query('type');
        $contact = $type === 'email' ? session('pending_email') : session('pending_phone');

        if (! $contact || ! in_array($type, ['email', 'phone'])) {
            return redirect()->route('customer.profile.show');
        }

        return view('customer.profile.verify-change', compact('type', 'contact'));
    }

    public function verifyChange(Request $request): RedirectResponse
    {
        $verifyData = VerifyCodeData::validateAndCreate($request->all());
        $originalCustomer = $request->user();

        $updateData = new UpdateContactData(
            email: $verifyData->type === 'email' ? session('pending_email') : null,
            phone: $verifyData->type === 'phone' ? session('pending_phone') : null,
        );

        $updatedCustomer = UpdateCustomerContactAction::execute(
            customer: $originalCustomer,
            data: $updateData,
            verifyData: $verifyData
        );

        $contactChanged = ($verifyData->type === 'email' && (string) $updatedCustomer->email !== (string) $originalCustomer->email)
            || ($verifyData->type === 'phone' && (string) $updatedCustomer->phone !== (string) $originalCustomer->phone);

        if (! $contactChanged) {
            return back()->withErrors(['code' => 'Невірний код підтвердження або код застарів. Спробуйте ще раз.']);
        }

        session()->forget(['pending_email', 'pending_phone']);

        return redirect()->route('customer.profile.show')
            ->with('success', __('messages.profile.contact_updated'));
    }

    public function updateProfileFields(Request $request): RedirectResponse
    {
        $customer = $request->user();
        $profileFieldsData = $request->input('profile_fields', []);

        try {
            SaveCustomerProfileFieldValuesAction::execute($customer, $profileFieldsData);

            return redirect()->route('customer.profile.show')
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
            return redirect()->route('customer.profile.edit');
        }

        $customer = $request->user();
        $purpose = $type === 'email' ? 'change_email' : 'change_phone';

        try {
            SendVerificationCodeAction::execute(
                type: $type,
                contact: $contact,
                purpose: $purpose,
                customerId: $customer->id
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
