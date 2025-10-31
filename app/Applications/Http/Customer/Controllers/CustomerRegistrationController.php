<?php

namespace App\Applications\Http\Customer\Controllers;

use App\Domains\Customer\Actions\AuthenticateCustomerAction;
use App\Domains\Customer\Actions\RegisterCustomerAction;
use App\Domains\Customer\Actions\SendVerificationCodeAction;
use App\Domains\Customer\Actions\VerifyCodeAction;
use App\Domains\Customer\Data\RegisterCustomerData;
use App\Domains\Customer\Data\VerifyCodeData;
use App\Domains\Customer\Exceptions\VerificationRateLimitException;
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

        $expiresAt = now()->addMinutes(15)->timestamp;

        session([
            'customer_id' => $customer->id,
            'customer_email' => $customer->email->value,
            'customer_phone' => $customer->phone->value,
            'phone_code_expires_at' => $expiresAt,
            'email_code_expires_at' => $expiresAt,
        ]);

        return redirect()->route('customer.verify-phone.show');
    }

    public function showVerifyPhone(): View|RedirectResponse
    {
        $customerId = session('customer_id');

        if (! $customerId) {
            return redirect()->route('customer.register');
        }

        return view('customer.auth.verify-phone');
    }

    public function verifyPhone(Request $request): RedirectResponse
    {
        $data = VerifyCodeData::validateAndCreate($request->all());

        $customer = VerifyCodeAction::execute($data);

        if (!$customer) {
            return back()->withErrors(['code' => __('messages.verification.invalid_code')]);
        }

        return redirect()->route('customer.verify-email.show')->with('success', __('messages.verification.phone_verified'));
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
            return back()->withErrors(['code' => __('messages.verification.invalid_code')]);
        }

        AuthenticateCustomerAction::execute($customer);

        session()->forget(['customer_id', 'customer_email', 'customer_phone', 'phone_code_expires_at', 'email_code_expires_at']);

        return redirect()->route('customer.profile.show')->with('success', __('messages.verification.email_verified'));
    }

    public function resendCode(Request $request): RedirectResponse
    {
        $request->validate([
            'type' => ['required', 'in:email,phone'],
        ]);

        $type = $request->input('type');
        $customerId = session('customer_id');

        if (!$customerId) {
            return redirect()->route('customer.register');
        }

        $contact = $type === 'email' ? session('customer_email') : session('customer_phone');

        if (!$contact) {
            return back()->withErrors(['type' => __('messages.errors.contact_not_found')]);
        }

        try {
            SendVerificationCodeAction::execute(
                type: $type,
                contact: $contact,
                purpose: 'registration',
                customerId: $customerId
            );

            $expiresAt = now()->addMinutes(15)->timestamp;
            $sessionKey = $type === 'email' ? 'email_code_expires_at' : 'phone_code_expires_at';
            session([$sessionKey => $expiresAt]);
            session()->forget('next_resend_at');

            $contactType = $type === 'email' ? __('messages.verification.code_resent_email') : __('messages.verification.code_resent_phone');

            return back()->with('success', __('messages.verification.code_resent', ['type' => $contactType]));
        } catch (VerificationRateLimitException $e) {
            $nextResendAt = now()->addSeconds($e->secondsUntilResend)->timestamp;
            session(['next_resend_at' => $nextResendAt]);

            return back()->withErrors(['resend' => $e->getMessage()]);
        }
    }
}
