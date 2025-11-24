<?php

namespace App\Applications\Http\Student\Controllers;

use App\Domains\Shared\Rules\ValidEmail;
use App\Domains\Shared\Rules\ValidPhone;
use App\Domains\Student\Actions\AuthenticateStudentAction;
use App\Domains\Student\Actions\GetActiveProfileFieldsAction;
use App\Domains\Student\Actions\RegisterStudentAction;
use App\Domains\Student\Actions\SaveContactDetailsAction;
use App\Domains\Student\Actions\SaveStudentProfileFieldValuesAction;
use App\Domains\Student\Actions\SendVerificationCodeAction;
use App\Domains\Student\Actions\VerifyCodeAction;
use App\Domains\Student\Data\ContactDetailsData;
use App\Domains\Student\Data\RegisterStudentData;
use App\Domains\Student\Data\VerifyCodeData;
use App\Domains\Student\Exceptions\VerificationRateLimitException;
use App\Domains\Student\Models\Student;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentRegistrationController
{
    public function showRegistrationForm(): View
    {
        return view('student.auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $emailVerified = session('email_verified');
        $phoneVerified = session('phone_verified');
        $verifiedEmail = session('verified_email');
        $verifiedPhone = session('verified_phone');

        if (! $emailVerified || ! $phoneVerified) {
            return redirect()->route('student.register')->withErrors([
                'error' => 'Будь ласка, підтвердіть email і телефон перед реєстрацією.',
            ]);
        }

        if (! $verifiedEmail || ! $verifiedPhone) {
            return redirect()->route('student.register')->withErrors([
                'error' => 'Помилка реєстрації. Спробуйте ще раз.',
            ]);
        }

        $data = RegisterStudentData::validateAndCreate([
            'email' => $verifiedEmail,
            'phone' => $verifiedPhone,
        ]);

        $student = RegisterStudentAction::execute($data);

        $student->markEmailAsVerified();
        $student->markPhoneAsVerified();

        session([
            'student_id' => $student->id,
            'student_email' => $student->email->value,
            'student_phone' => $student->phone->value,
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

        return redirect()->route('student.contact-details.show');
    }

    public function showVerifyPhone(): View|RedirectResponse
    {
        $studentId = session('student_id');

        if (! $studentId) {
            return redirect()->route('student.register');
        }

        return view('student.auth.verify-phone');
    }

    public function verifyPhone(Request $request): RedirectResponse
    {
        $data = VerifyCodeData::validateAndCreate($request->all());

        $student = VerifyCodeAction::execute($data);

        if (! $student) {
            return back()->withErrors(['code' => __('messages.verification.invalid_code')]);
        }

        return redirect()->route('student.verify-email.show')->with('success', __('messages.verification.phone_verified'));
    }

    public function showVerifyEmail(): View
    {
        $studentId = session('student_id');

        if (! $studentId) {
            return redirect()->route('student.register');
        }

        return view('student.auth.verify-email');
    }

    public function verifyEmail(Request $request): RedirectResponse
    {
        $data = VerifyCodeData::validateAndCreate($request->all());

        $student = VerifyCodeAction::execute($data);

        if (! $student) {
            return back()->withErrors(['code' => __('messages.verification.invalid_code')]);
        }

        return redirect()->route('student.contact-details.show')->with('success', __('messages.verification.email_verified'));
    }

    public function showContactDetails(): View|RedirectResponse
    {
        $studentId = session('student_id');

        if (! $studentId) {
            return redirect()->route('student.register');
        }

        $student = Student::find($studentId);

        if (! $student || ! $student->isFullyVerified()) {
            return redirect()->route('student.register');
        }

        return view('student.auth.contact-details');
    }

    public function saveContactDetails(Request $request): RedirectResponse
    {
        $studentId = session('student_id');

        if (! $studentId) {
            return redirect()->route('student.register');
        }

        $student = Student::find($studentId);

        if (! $student || ! $student->isFullyVerified()) {
            return redirect()->route('student.register');
        }

        $data = ContactDetailsData::validateAndCreate($request->all());

        SaveContactDetailsAction::execute($student, $data);

        return redirect()->route('student.profile-fields.show')->with('success', __('messages.contact_details.saved'));
    }

    public function showProfileFields(): View|RedirectResponse
    {
        $studentId = session('student_id');

        if (! $studentId) {
            return redirect()->route('student.register');
        }

        $student = Student::find($studentId);

        if (! $student || ! $student->isFullyVerified() || ! $student->hasContactDetails()) {
            return redirect()->route('student.register');
        }

        $fields = GetActiveProfileFieldsAction::execute();

        return view('student.auth.profile-fields', compact('fields'));
    }

    public function saveProfileFields(Request $request): RedirectResponse
    {
        $studentId = session('student_id');

        if (! $studentId) {
            return redirect()->route('student.register');
        }

        $student = Student::find($studentId);

        if (! $student || ! $student->isFullyVerified() || ! $student->hasContactDetails()) {
            return redirect()->route('student.register');
        }

        $fieldValues = $request->input('profile_fields', []);

        SaveStudentProfileFieldValuesAction::execute($student, $fieldValues);

        AuthenticateStudentAction::execute($student);

        session()->forget(['student_id', 'student_email', 'student_phone', 'phone_code_expires_at', 'email_code_expires_at']);

        return redirect()->route('student.profile.show')->with('success', __('messages.profile_fields.saved'));
    }

    public function skipProfileFields(): RedirectResponse
    {
        $studentId = session('student_id');

        if (! $studentId) {
            return redirect()->route('student.register');
        }

        $student = Student::find($studentId);

        if (! $student) {
            return redirect()->route('student.register');
        }

        AuthenticateStudentAction::execute($student);

        session()->forget(['student_id', 'student_email', 'student_phone', 'phone_code_expires_at', 'email_code_expires_at']);

        return redirect()->route('student.profile.show')->with('info', __('messages.profile_fields.skipped'));
    }

    public function resendCode(Request $request): RedirectResponse
    {
        $request->validate([
            'type' => ['required', 'in:email,phone'],
        ]);

        $type = $request->input('type');
        $studentId = session('student_id');

        if (! $studentId) {
            return redirect()->route('student.register');
        }

        $contact = $type === 'email' ? session('student_email') : session('student_phone');

        if (! $contact) {
            return back()->withErrors(['type' => __('messages.errors.contact_not_found')]);
        }

        try {
            SendVerificationCodeAction::execute(
                type: $type,
                contact: $contact,
                purpose: 'registration',
                studentId: $studentId
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
            $exists = Student::where('email', $contact)->exists();
            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => __('validation.custom.email.unique'),
                ], 422);
            }
        } else {
            $exists = Student::where('phone', $contact)->exists();
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
                studentId: null
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

        $verification = VerifyCodeAction::verifyWithoutStudent($data);

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
                studentId: null
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
