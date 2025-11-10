<?php

namespace App\Applications\Http\Customer\Controllers;

use App\Domains\ActivityLog\Actions\LogActivityAction;
use App\Domains\ActivityLog\Data\ActivityLogData;
use App\Domains\ActivityLog\Enums\ActivitySubject;
use App\Domains\ActivityLog\Enums\ActivityType;
use App\Domains\Customer\Actions\AuthenticateCustomerAction;
use App\Domains\Customer\Actions\SendVerificationCodeAction;
use App\Domains\Customer\Actions\VerifyCodeAction;
use App\Domains\Customer\Data\LoginCustomerData;
use App\Domains\Customer\Data\VerifyCodeData;
use App\Domains\Customer\Exceptions\VerificationRateLimitException;
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

        if (! $customer) {
            LogActivityAction::execute(ActivityLogData::from([
                'subject_type' => ActivitySubject::Customer,
                'subject_id' => null,
                'activity_type' => ActivityType::CustomerLoginFailed,
                'description' => 'Login attempt failed - customer not found',
                'properties' => [
                    'contact' => $data->contact,
                    'contact_type' => $data->isEmail() ? 'email' : 'phone',
                    'reason' => 'customer_not_found',
                ],
                'ip_address' => null,
                'user_agent' => null,
            ]));

            return back()->withErrors(['contact' => __('messages.errors.customer_not_found')]);
        }

        try {
            SendVerificationCodeAction::execute(
                type: $data->isEmail() ? 'email' : 'phone',
                contact: $data->contact,
                purpose: 'login',
                customerId: $customer->id
            );

            session(['login_contact' => $data->contact]);
            session(['login_type' => $data->isEmail() ? 'email' : 'phone']);
            session()->forget('next_resend_at');

            return redirect()->route('customer.verify-login.show');
        } catch (VerificationRateLimitException $e) {
            $nextResendAt = now()->addSeconds($e->secondsUntilResend)->timestamp;
            session(['next_resend_at' => $nextResendAt]);

            return back()->withErrors(['rate_limit' => $e->getMessage()]);
        }
    }

    public function showVerifyLogin(): View|RedirectResponse
    {
        $contact = session('login_contact');
        $type = session('login_type');

        if (! $contact || ! $type) {
            return redirect()->route('customer.login');
        }

        return view('customer.auth.verify-login', compact('contact', 'type'));
    }

    public function verifyLogin(Request $request): RedirectResponse
    {
        $data = VerifyCodeData::validateAndCreate($request->all());

        $customer = VerifyCodeAction::execute($data);

        if (! $customer) {
            return back()->withErrors(['code' => __('messages.errors.invalid_code')]);
        }

        if (! $customer->isFullyVerified()) {
            session()->forget(['login_contact', 'login_type']);
            session(['verification_customer_id' => $customer->id]);

            return redirect()->route('customer.complete-verification');
        }

        AuthenticateCustomerAction::execute($customer);

        session()->forget(['login_contact', 'login_type']);

        return redirect()->route('customer.profile.show');
    }

    public function resendLoginCode(): RedirectResponse
    {
        $contact = session('login_contact');
        $type = session('login_type');

        if (! $contact || ! $type) {
            return redirect()->route('customer.login');
        }

        $customer = Customer::query()
            ->where($type === 'email' ? 'email' : 'phone', $contact)
            ->first();

        if (! $customer) {
            return redirect()->route('customer.login');
        }

        try {
            SendVerificationCodeAction::execute(
                type: $type,
                contact: $contact,
                purpose: 'login',
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

    public function showCompleteVerification(): View|RedirectResponse
    {
        $customerId = session('verification_customer_id') ?? Auth::id();

        if (! $customerId) {
            return redirect()->route('customer.login');
        }

        $customer = Customer::find($customerId);

        if (! $customer) {
            return redirect()->route('customer.login');
        }

        if ($customer->isFullyVerified()) {
            AuthenticateCustomerAction::execute($customer);
            session()->forget('verification_customer_id');

            return redirect()->route('customer.profile.show');
        }

        $verificationStep = ! $customer->hasVerifiedPhone() ? 'phone' : 'email';
        $contact = $verificationStep === 'phone'
            ? $customer->phone
            : $customer->email;

        if (! session()->has('verification_code_sent')) {
            SendVerificationCodeAction::execute(
                type: $verificationStep,
                contact: $contact,
                purpose: 'verification',
                customerId: $customer->id
            );

            session(['verification_code_sent' => true]);
        }

        return view('customer.auth.complete-verification', compact('verificationStep', 'contact'));
    }

    public function verifyCompleteVerification(Request $request): RedirectResponse
    {
        $data = VerifyCodeData::validateAndCreate($request->all());

        $customer = VerifyCodeAction::execute($data);

        if (! $customer) {
            return back()->withErrors(['code' => __('messages.errors.invalid_code')]);
        }

        session()->forget('verification_code_sent');

        if (! $customer->isFullyVerified()) {
            return redirect()->route('customer.complete-verification');
        }

        AuthenticateCustomerAction::execute($customer);
        session()->forget('verification_customer_id');

        return redirect()->route('customer.profile.show');
    }

    public function resendVerificationCode(): RedirectResponse
    {
        $customerId = session('verification_customer_id') ?? Auth::id();

        if (! $customerId) {
            return redirect()->route('customer.login');
        }

        $customer = Customer::find($customerId);

        if (! $customer) {
            return redirect()->route('customer.login');
        }

        $verificationStep = ! $customer->hasVerifiedPhone() ? 'phone' : 'email';
        $contact = $verificationStep === 'phone'
            ? $customer->phone
            : $customer->email;

        try {
            SendVerificationCodeAction::execute(
                type: $verificationStep,
                contact: $contact,
                purpose: 'verification',
                customerId: $customer->id
            );

            session(['verification_code_sent' => true]);
            session()->forget('next_resend_at');

            return back()->with('status', __('messages.success.verification_code_sent'));
        } catch (VerificationRateLimitException $e) {
            $nextResendAt = now()->addSeconds($e->secondsUntilResend)->timestamp;
            session(['next_resend_at' => $nextResendAt]);

            return back()->withErrors(['resend' => $e->getMessage()]);
        }
    }

    public function logout(Request $request): RedirectResponse
    {
        $customerId = Auth::id();

        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($customerId) {
            LogActivityAction::execute(ActivityLogData::from([
                'subject_type' => ActivitySubject::Customer,
                'subject_id' => $customerId,
                'activity_type' => ActivityType::CustomerLoggedOut,
                'description' => 'Customer logged out',
                'properties' => [
                    'reason' => 'user_initiated',
                ],
                'ip_address' => null,
                'user_agent' => null,
            ]));
        }

        return redirect()->route('customer.login');
    }
}
