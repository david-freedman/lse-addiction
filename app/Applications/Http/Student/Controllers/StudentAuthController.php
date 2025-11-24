<?php

namespace App\Applications\Http\Student\Controllers;

use App\Domains\ActivityLog\Actions\LogActivityAction;
use App\Domains\ActivityLog\Data\ActivityLogData;
use App\Domains\ActivityLog\Enums\ActivitySubject;
use App\Domains\ActivityLog\Enums\ActivityType;
use App\Domains\Student\Actions\AuthenticateStudentAction;
use App\Domains\Student\Actions\SendVerificationCodeAction;
use App\Domains\Student\Actions\VerifyCodeAction;
use App\Domains\Student\Data\LoginStudentData;
use App\Domains\Student\Data\VerifyCodeData;
use App\Domains\Student\Exceptions\VerificationRateLimitException;
use App\Domains\Student\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class StudentAuthController
{
    public function showLoginForm(): View
    {
        return view('student.auth.login');
    }

    public function sendLoginCode(Request $request): RedirectResponse
    {
        $data = LoginStudentData::validateAndCreate($request->all());

        $student = Student::query()
            ->where($data->isEmail() ? 'email' : 'phone', $data->contact)
            ->first();

        if (! $student) {
            LogActivityAction::execute(ActivityLogData::from([
                'subject_type' => ActivitySubject::Student,
                'subject_id' => null,
                'activity_type' => ActivityType::StudentLoginFailed,
                'description' => 'Login attempt failed - student not found',
                'properties' => [
                    'contact' => $data->contact,
                    'contact_type' => $data->isEmail() ? 'email' : 'phone',
                    'reason' => 'student_not_found',
                ],
                'ip_address' => null,
                'user_agent' => null,
            ]));

            return back()->withErrors(['contact' => __('messages.errors.student_not_found')]);
        }

        try {
            SendVerificationCodeAction::execute(
                type: $data->isEmail() ? 'email' : 'phone',
                contact: $data->contact,
                purpose: 'login',
                studentId: $student->id
            );

            session(['login_contact' => $data->contact]);
            session(['login_type' => $data->isEmail() ? 'email' : 'phone']);
            session()->forget('next_resend_at');

            return redirect()->route('student.verify-login.show');
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
            return redirect()->route('student.login');
        }

        return view('student.auth.verify-login', compact('contact', 'type'));
    }

    public function verifyLogin(Request $request): RedirectResponse
    {
        $data = VerifyCodeData::validateAndCreate($request->all());

        $student = VerifyCodeAction::execute($data);

        if (! $student) {
            return back()->withErrors(['code' => __('messages.errors.invalid_code')]);
        }

        if (! $student->isFullyVerified()) {
            session()->forget(['login_contact', 'login_type']);
            session(['verification_student_id' => $student->id]);

            return redirect()->route('student.complete-verification');
        }

        AuthenticateStudentAction::execute($student);

        session()->forget(['login_contact', 'login_type']);

        return redirect()->route('student.profile.show');
    }

    public function resendLoginCode(): RedirectResponse
    {
        $contact = session('login_contact');
        $type = session('login_type');

        if (! $contact || ! $type) {
            return redirect()->route('student.login');
        }

        $student = Student::query()
            ->where($type === 'email' ? 'email' : 'phone', $contact)
            ->first();

        if (! $student) {
            return redirect()->route('student.login');
        }

        try {
            SendVerificationCodeAction::execute(
                type: $type,
                contact: $contact,
                purpose: 'login',
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

    public function showCompleteVerification(): View|RedirectResponse
    {
        $studentId = session('verification_student_id') ?? Auth::id();

        if (! $studentId) {
            return redirect()->route('student.login');
        }

        $student = Student::find($studentId);

        if (! $student) {
            return redirect()->route('student.login');
        }

        if ($student->isFullyVerified()) {
            AuthenticateStudentAction::execute($student);
            session()->forget('verification_student_id');

            return redirect()->route('student.profile.show');
        }

        $requirePhone = config('verification.require_phone', true);

        $verificationStep = ($requirePhone && ! $student->hasVerifiedPhone())
            ? 'phone'
            : 'email';
        $contact = $verificationStep === 'phone'
            ? $student->phone
            : $student->email;

        if (! session()->has('verification_code_sent')) {
            SendVerificationCodeAction::execute(
                type: $verificationStep,
                contact: $contact,
                purpose: 'verification',
                studentId: $student->id
            );

            session(['verification_code_sent' => true]);
        }

        return view('student.auth.complete-verification', compact('verificationStep', 'contact'));
    }

    public function verifyCompleteVerification(Request $request): RedirectResponse
    {
        $data = VerifyCodeData::validateAndCreate($request->all());

        $student = VerifyCodeAction::execute($data);

        if (! $student) {
            return back()->withErrors(['code' => __('messages.errors.invalid_code')]);
        }

        session()->forget('verification_code_sent');

        if (! $student->isFullyVerified()) {
            return redirect()->route('student.complete-verification');
        }

        AuthenticateStudentAction::execute($student);
        session()->forget('verification_student_id');

        return redirect()->route('student.profile.show');
    }

    public function resendVerificationCode(): RedirectResponse
    {
        $studentId = session('verification_student_id') ?? Auth::id();

        if (! $studentId) {
            return redirect()->route('student.login');
        }

        $student = Student::find($studentId);

        if (! $student) {
            return redirect()->route('student.login');
        }

        $requirePhone = config('verification.require_phone', true);

        $verificationStep = ($requirePhone && ! $student->hasVerifiedPhone())
            ? 'phone'
            : 'email';
        $contact = $verificationStep === 'phone'
            ? $student->phone
            : $student->email;

        try {
            SendVerificationCodeAction::execute(
                type: $verificationStep,
                contact: $contact,
                purpose: 'verification',
                studentId: $student->id
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
        $studentId = Auth::id();

        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($studentId) {
            LogActivityAction::execute(ActivityLogData::from([
                'subject_type' => ActivitySubject::Student,
                'subject_id' => $studentId,
                'activity_type' => ActivityType::StudentLoggedOut,
                'description' => 'Student logged out',
                'properties' => [
                    'reason' => 'user_initiated',
                ],
                'ip_address' => null,
                'user_agent' => null,
            ]));
        }

        return redirect()->route('student.login');
    }
}
