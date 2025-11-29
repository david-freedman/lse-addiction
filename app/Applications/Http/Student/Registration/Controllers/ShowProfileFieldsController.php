<?php

namespace App\Applications\Http\Student\Registration\Controllers;

use App\Domains\Student\Actions\GetActiveProfileFieldsAction;
use App\Domains\Student\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

final class ShowProfileFieldsController
{
    public function __invoke(): View|RedirectResponse
    {
        $studentId = session('student_id');

        if (!$studentId) {
            return redirect()->route('student.register');
        }

        $student = Student::find($studentId);

        if (!$student || !$student->isFullyVerified() || !$student->hasContactDetails()) {
            return redirect()->route('student.register');
        }

        $fields = GetActiveProfileFieldsAction::execute();

        return view('student.auth.profile-fields', compact('fields'));
    }
}
