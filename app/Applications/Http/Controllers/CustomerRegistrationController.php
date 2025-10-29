<?php

namespace App\Applications\Http\Controllers;

use App\Domains\Customer\Actions\AuthenticateCustomerAction;
use App\Domains\Customer\Actions\RegisterCustomerAction;
use App\Domains\Customer\Actions\VerifyCodeAction;
use App\Domains\Customer\Data\RegisterCustomerData;
use App\Domains\Customer\Data\VerifyCodeData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerRegistrationController
{
    public function showRegistrationForm(): View
    {
        return view('customer.auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $data = RegisterCustomerData::validateAndCreate($request->all());

        $customer = RegisterCustomerAction::execute($data);

        session(['customer_id' => $customer->id]);

        return redirect()->route('customer.verify-phone.show');
    }

    public function showVerifyPhone(): View
    {
        $customerId = session('customer_id');

        if (!$customerId) {
            return redirect()->route('customer.register');
        }

        return view('customer.auth.verify-phone');
    }

    public function verifyPhone(Request $request): RedirectResponse
    {
        $data = VerifyCodeData::validateAndCreate($request->all());

        $customer = VerifyCodeAction::execute($data);

        if (!$customer) {
            return back()->withErrors(['code' => 'Invalid or expired code']);
        }

        return redirect()->route('customer.verify-email.show');
    }

    public function showVerifyEmail(): View
    {
        $customerId = session('customer_id');

        if (!$customerId) {
            return redirect()->route('customer.register');
        }

        return view('customer.auth.verify-email');
    }

    public function verifyEmail(Request $request): RedirectResponse
    {
        $data = VerifyCodeData::validateAndCreate($request->all());

        $customer = VerifyCodeAction::execute($data);

        if (!$customer) {
            return back()->withErrors(['code' => 'Invalid or expired code']);
        }

        AuthenticateCustomerAction::execute($customer);

        session()->forget('customer_id');

        return redirect()->route('customer.profile.show');
    }
}
