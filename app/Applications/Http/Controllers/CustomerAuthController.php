<?php

namespace App\Applications\Http\Controllers;

use App\Domains\Customer\Actions\AuthenticateCustomerAction;
use App\Domains\Customer\Actions\SendVerificationCodeAction;
use App\Domains\Customer\Actions\VerifyCodeAction;
use App\Domains\Customer\Data\LoginCustomerData;
use App\Domains\Customer\Data\VerifyCodeData;
use App\Domains\Customer\Models\Customer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CustomerAuthController
{
    public function showLoginForm(): View
    {
        return view('customer.auth.login');
    }

    public function sendLoginCode(Request $request): RedirectResponse
    {
        $data = LoginCustomerData::validateAndCreate($request->all());

        $customer = Customer::query()
            ->where($data->isEmail() ? 'email' : 'phone', $data->contact)
            ->first();

        if (!$customer) {
            return back()->withErrors(['contact' => 'Customer not found']);
        }

        SendVerificationCodeAction::execute(
            type: $data->isEmail() ? 'email' : 'phone',
            contact: $data->contact,
            purpose: 'login',
            customerId: $customer->id
        );

        session(['login_contact' => $data->contact]);
        session(['login_type' => $data->isEmail() ? 'email' : 'phone']);

        return redirect()->route('customer.verify-login.show');
    }

    public function showVerifyLogin(): View
    {
        $contact = session('login_contact');
        $type = session('login_type');

        if (!$contact || !$type) {
            return redirect()->route('customer.login');
        }

        return view('customer.auth.verify-login', compact('contact', 'type'));
    }

    public function verifyLogin(Request $request): RedirectResponse
    {
        $data = VerifyCodeData::validateAndCreate($request->all());

        $customer = VerifyCodeAction::execute($data);

        if (!$customer) {
            return back()->withErrors(['code' => 'Invalid or expired code']);
        }

        AuthenticateCustomerAction::execute($customer);

        session()->forget(['login_contact', 'login_type']);

        return redirect()->route('customer.profile.show');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('customer.login');
    }
}
