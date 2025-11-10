<?php

namespace App\Applications\Http\Customer\Controllers;

use App\Domains\Customer\Actions\SendVerificationCodeAction;
use App\Domains\Customer\Actions\UpdateCustomerContactAction;
use App\Domains\Customer\Actions\UpdateCustomerPersonalDetailsAction;
use App\Domains\Customer\Data\UpdateContactData;
use App\Domains\Customer\Data\UpdatePersonalDetailsData;
use App\Domains\Customer\Data\VerifyCodeData;
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

        return view('customer.profile.edit', compact('customer'));
    }

    public function update(Request $request): RedirectResponse
    {
        $customer = $request->user();
        $hasPersonalDetailsChanges = false;
        $hasContactChanges = false;

        if ($request->has(['surname', 'name', 'birthday', 'city'])) {
            try {
                $personalData = UpdatePersonalDetailsData::validateAndCreate($request->only(['surname', 'name', 'birthday', 'city']));

                UpdateCustomerPersonalDetailsAction::execute($customer, $personalData);
                $hasPersonalDetailsChanges = true;
            } catch (\Exception $e) {
                return back()->withErrors(['personal_details' => $e->getMessage()])->withInput();
            }
        }

        if ($request->has(['email', 'phone'])) {
            $contactData = UpdateContactData::validateAndCreate($request->only(['email', 'phone']));

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

        if ($hasPersonalDetailsChanges) {
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

        $updateData = new UpdateContactData(
            email: $verifyData->type === 'email' ? session('pending_email') : null,
            phone: $verifyData->type === 'phone' ? session('pending_phone') : null,
        );

        $customer = UpdateCustomerContactAction::execute(
            customer: $request->user(),
            data: $updateData,
            verifyData: $verifyData
        );

        session()->forget(['pending_email', 'pending_phone']);

        return redirect()->route('customer.profile.show')
            ->with('success', __('messages.profile.contact_updated'));
    }
}
