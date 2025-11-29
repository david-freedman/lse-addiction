<?php

namespace App\Applications\Http\Student\Auth\Controllers;

use App\Domains\ActivityLog\Actions\LogActivityAction;
use App\Domains\ActivityLog\Data\ActivityLogData;
use App\Domains\ActivityLog\Enums\ActivitySubject;
use App\Domains\ActivityLog\Enums\ActivityType;
use App\Domains\Student\Actions\SendVerificationCodeAction;
use App\Domains\Student\Data\LoginStudentData;
use App\Domains\Student\Exceptions\VerificationRateLimitException;
use App\Domains\Student\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class SendLoginCodeController
{
    public function __invoke(Request $request): RedirectResponse
    {
        $data = LoginStudentData::validateAndCreate($request->all());

        $student = Student::query()
            ->where($data->isEmail() ? 'email' : 'phone', $data->contact)
            ->first();

        if (!$student) {
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
}
