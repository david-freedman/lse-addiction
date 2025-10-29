<?php

namespace App\Applications\Http\Controllers;

use App\Domains\Customer\Actions\SendVerificationCodeAction;
use App\Domains\Customer\Actions\UpdateCustomerContactAction;
use App\Domains\Customer\Data\UpdateContactData;
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
        $data = UpdateContactData::validateAndCreate($request->all());

        if ($data->email) {
            SendVerificationCodeAction::execute(
                type: 'email',
                contact: $data->email,
                purpose: 'change_email',
                customerId: $request->user()->id
            );

            session(['pending_email' => $data->email]);

            return redirect()->route('customer.verify-change.show', ['type' => 'email']);
        }

        if ($data->phone) {
            SendVerificationCodeAction::execute(
                type: 'phone',
                contact: $data->phone,
                purpose: 'change_phone',
                customerId: $request->user()->id
            );

            session(['pending_phone' => $data->phone]);

            return redirect()->route('customer.verify-change.show', ['type' => 'phone']);
        }

        return back()->withErrors(['error' => 'No changes detected']);
    }

    public function showVerifyChange(Request $request): View
    {
        $type = $request->query('type');
        $contact = $type === 'email' ? session('pending_email') : session('pending_phone');

        if (!$contact || !in_array($type, ['email', 'phone'])) {
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
            ->with('success', 'Contact updated successfully');
    }
}
