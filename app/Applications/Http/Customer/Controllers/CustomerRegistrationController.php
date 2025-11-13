<?php

namespace App\Applications\Http\Customer\Controllers;

use App\Domains\Customer\Actions\AuthenticateCustomerAction;
use App\Domains\Customer\Actions\GetActiveProfileFieldsAction;
use App\Domains\Customer\Actions\RegisterCustomerAction;
use App\Domains\Customer\Actions\SaveContactDetailsAction;
use App\Domains\Customer\Actions\SaveCustomerProfileFieldValuesAction;
use App\Domains\Customer\Actions\SendVerificationCodeAction;
use App\Domains\Customer\Actions\VerifyCodeAction;
use App\Domains\Customer\Data\ContactDetailsData;
use App\Domains\Customer\Data\RegisterCustomerData;
use App\Domains\Customer\Data\VerifyCodeData;
use App\Domains\Customer\Exceptions\VerificationRateLimitException;
use App\Domains\Customer\Models\Customer;
use App\Domains\Shared\Rules\ValidEmail;
use App\Domains\Shared\Rules\ValidPhone;
use Illuminate\Http\JsonResponse;
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
        $emailVerified = session('email_verified');
        $phoneVerified = session('phone_verified');
        $verifiedEmail = session('verified_email');
        $verifiedPhone = session('verified_phone');

        if (! $emailVerified || ! $phoneVerified) {
            return redirect()->route('customer.register')->withErrors([
                'error' => 'Будь ласка, підтвердіть email і телефон перед реєстрацією.',
            ]);
        }

        if (! $verifiedEmail || ! $verifiedPhone) {
            return redirect()->route('customer.register')->withErrors([
                'error' => 'Помилка реєстрації. Спробуйте ще раз.',
            ]);
        }

        $data = RegisterCustomerData::validateAndCreate([
            'email' => $verifiedEmail,
            'phone' => $verifiedPhone,
        ]);

        $customer = RegisterCustomerAction::execute($data);

        $customer->markEmailAsVerified();
        $customer->markPhoneAsVerified();

        session([
            'customer_id' => $customer->id,
            'customer_email' => $customer->email->value,
            'customer_phone' => $customer->phone->value,
        ]);

        session()->forget([
            'email_verified',
            'phone_verified',
            'verified_email',
            'verified_phone',
            'registration_email',
            'registration_phone',
            'email_code_expires_at',
            'phone_code_expires_at',
        ]);

        return redirect()->route('customer.contact-details.show');
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

        if (! $customer) {
            return back()->withErrors(['code' => __('messages.verification.invalid_code')]);
        }

        return redirect()->route('customer.verify-email.show')->with('success', __('messages.verification.phone_verified'));
    }

    public function showVerifyEmail(): View
    {
        $customerId = session('customer_id');

        if (! $customerId) {
            return redirect()->route('customer.register');
        }

        return view('customer.auth.verify-email');
    }

    public function verifyEmail(Request $request): RedirectResponse
    {
        $data = VerifyCodeData::validateAndCreate($request->all());

        $customer = VerifyCodeAction::execute($data);

        if (! $customer) {
            return back()->withErrors(['code' => __('messages.verification.invalid_code')]);
        }

        return redirect()->route('customer.contact-details.show')->with('success', __('messages.verification.email_verified'));
    }

    public function showContactDetails(): View|RedirectResponse
    {
        $customerId = session('customer_id');

        if (! $customerId) {
            return redirect()->route('customer.register');
        }

        $customer = Customer::find($customerId);

        if (! $customer || ! $customer->isFullyVerified()) {
            return redirect()->route('customer.register');
        }

        return view('customer.auth.contact-details');
    }

    public function saveContactDetails(Request $request): RedirectResponse
    {
        $customerId = session('customer_id');

        if (! $customerId) {
            return redirect()->route('customer.register');
        }

        $customer = Customer::find($customerId);

        if (! $customer || ! $customer->isFullyVerified()) {
            return redirect()->route('customer.register');
        }

        $data = ContactDetailsData::validateAndCreate($request->all());

        SaveContactDetailsAction::execute($customer, $data);

        return redirect()->route('customer.profile-fields.show')->with('success', __('messages.contact_details.saved'));
    }

    public function showProfileFields(): View|RedirectResponse
    {
        $customerId = session('customer_id');

        if (! $customerId) {
            return redirect()->route('customer.register');
        }

        $customer = Customer::find($customerId);

        if (! $customer || ! $customer->isFullyVerified() || ! $customer->hasContactDetails()) {
            return redirect()->route('customer.register');
        }

        $fields = GetActiveProfileFieldsAction::execute();

        return view('customer.auth.profile-fields', compact('fields'));
    }

    public function saveProfileFields(Request $request): RedirectResponse
    {
        $customerId = session('customer_id');

        if (! $customerId) {
            return redirect()->route('customer.register');
        }

        $customer = Customer::find($customerId);

        if (! $customer || ! $customer->isFullyVerified() || ! $customer->hasContactDetails()) {
            return redirect()->route('customer.register');
        }

        $fieldValues = $request->input('profile_fields', []);

        SaveCustomerProfileFieldValuesAction::execute($customer, $fieldValues);

        AuthenticateCustomerAction::execute($customer);

        session()->forget(['customer_id', 'customer_email', 'customer_phone', 'phone_code_expires_at', 'email_code_expires_at']);

        return redirect()->route('customer.profile.show')->with('success', __('messages.profile_fields.saved'));
    }

    public function skipProfileFields(): RedirectResponse
    {
        $customerId = session('customer_id');

        if (! $customerId) {
            return redirect()->route('customer.register');
        }

        $customer = Customer::find($customerId);

        if (! $customer) {
            return redirect()->route('customer.register');
        }

        AuthenticateCustomerAction::execute($customer);

        session()->forget(['customer_id', 'customer_email', 'customer_phone', 'phone_code_expires_at', 'email_code_expires_at']);

        return redirect()->route('customer.profile.show')->with('info', __('messages.profile_fields.skipped'));
    }

    public function resendCode(Request $request): RedirectResponse
    {
        $request->validate([
            'type' => ['required', 'in:email,phone'],
        ]);

        $type = $request->input('type');
        $customerId = session('customer_id');

        if (! $customerId) {
            return redirect()->route('customer.register');
        }

        $contact = $type === 'email' ? session('customer_email') : session('customer_phone');

        if (! $contact) {
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

    public function sendVerificationCode(Request $request): JsonResponse
    {
        $type = $request->input('type');

        $rules = [
            'type' => ['required', 'in:email,phone'],
        ];

        if ($type === 'email') {
            $rules['email'] = ['required', 'email', new ValidEmail];
        } else {
            $rules['phone'] = ['required', 'string', new ValidPhone];
        }

        $validated = $request->validate($rules);

        $contact = $type === 'email' ? $validated['email'] : $validated['phone'];

        if ($type === 'email') {
            $exists = Customer::where('email', $contact)->exists();
            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => __('validation.custom.email.unique'),
                ], 422);
            }
        } else {
            $exists = Customer::where('phone', $contact)->exists();
            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => __('validation.custom.phone.unique'),
                ], 422);
            }
        }

        try {
            SendVerificationCodeAction::execute(
                type: $type,
                contact: $contact,
                purpose: 'registration',
                customerId: null
            );

            $expiresAt = now()->addMinutes(15)->timestamp;
            $sessionKey = $type === 'email' ? 'email_code_expires_at' : 'phone_code_expires_at';
            $contactKey = $type === 'email' ? 'registration_email' : 'registration_phone';

            session([
                $sessionKey => $expiresAt,
                $contactKey => $contact,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Код верифікації відправлено',
            ]);
        } catch (VerificationRateLimitException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 429);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Помилка відправки коду',
            ], 500);
        }
    }

    public function verifyVerificationCode(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'size:4'],
            'type' => ['required', 'in:email,phone'],
        ]);

        $type = $validated['type'];
        $contactKey = $type === 'email' ? 'registration_email' : 'registration_phone';
        $contact = session($contactKey);

        if (! $contact) {
            return response()->json([
                'success' => false,
                'message' => 'Спочатку відправте код верифікації',
            ], 422);
        }

        $data = VerifyCodeData::validateAndCreate([
            'code' => $validated['code'],
            'type' => $type,
            'contact' => $contact,
        ]);

        $verification = VerifyCodeAction::verifyWithoutCustomer($data);

        if (! $verification) {
            return response()->json([
                'success' => false,
                'message' => 'Невірний код верифікації',
            ], 422);
        }

        $verifiedKey = $type === 'email' ? 'email_verified' : 'phone_verified';
        $verifiedContactKey = $type === 'email' ? 'verified_email' : 'verified_phone';

        session([
            $verifiedKey => true,
            $verifiedContactKey => $contact,
        ]);

        return response()->json([
            'success' => true,
            'message' => $type === 'email' ? 'Email верифіковано' : 'Телефон верифіковано',
            'verified' => true,
        ]);
    }

    public function resendVerificationCodeJson(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'type' => ['required', 'in:email,phone'],
        ]);

        $type = $validated['type'];
        $contactKey = $type === 'email' ? 'registration_email' : 'registration_phone';
        $contact = session($contactKey);

        if (! $contact) {
            return response()->json([
                'success' => false,
                'message' => 'Спочатку відправте код верифікації',
            ], 422);
        }

        try {
            SendVerificationCodeAction::execute(
                type: $type,
                contact: $contact,
                purpose: 'registration',
                customerId: null
            );

            $expiresAt = now()->addMinutes(15)->timestamp;
            $sessionKey = $type === 'email' ? 'email_code_expires_at' : 'phone_code_expires_at';

            session([
                $sessionKey => $expiresAt,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Код відправлено повторно',
                'expires_at' => $expiresAt,
            ]);
        } catch (VerificationRateLimitException $e) {
            $nextResendAt = now()->addSeconds($e->secondsUntilResend)->timestamp;

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'next_resend_at' => $nextResendAt,
            ], 429);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Помилка відправки коду',
            ], 500);
        }
    }
}
