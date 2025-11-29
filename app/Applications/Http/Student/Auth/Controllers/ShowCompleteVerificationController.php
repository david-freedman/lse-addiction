<?php

namespace App\Applications\Http\Student\Auth\Controllers;

use App\Domains\Student\Actions\AuthenticateStudentAction;
use App\Domains\Student\Actions\SendVerificationCodeAction;
use App\Domains\Student\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

final class ShowCompleteVerificationController
{
    public function __invoke(): View|RedirectResponse
    {
        $studentId = session('verification_student_id') ?? Auth::id();

        if (!$studentId) {
            return redirect()->route('student.login');
        }

        $student = Student::find($studentId);

        if (!$student) {
            return redirect()->route('student.login');
        }

        if ($student->isFullyVerified()) {
            AuthenticateStudentAction::execute($student);
            session()->forget('verification_student_id');

            return redirect()->route('student.profile.show');
        }

        $requirePhone = config('verification.require_phone', true);

        $verificationStep = ($requirePhone && !$student->hasVerifiedPhone())
            ? 'phone'
            : 'email';
        $contact = $verificationStep === 'phone'
            ? $student->phone
            : $student->email;

        if (!session()->has('verification_code_sent')) {
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
}
