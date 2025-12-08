<?php

namespace App\Applications\Http\Student\Registration\Controllers;

use App\Domains\Student\Actions\SaveContactDetailsAction;
use App\Domains\Student\Data\ContactDetailsData;
use App\Domains\Student\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class SaveContactDetailsController
{
    public function __invoke(Request $request): RedirectResponse
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
}
