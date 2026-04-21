<?php

namespace App\Applications\Http\Student\Webinar\Controllers;

use App\Domains\Webinar\Models\Webinar;
use Illuminate\View\View;

final class ShowWebinarController
{
    public function __invoke(Webinar $webinar): View
    {
        if ($webinar->isDraft()) {
            abort(404);
        }

        $webinar->load('teacher');

        $student = auth()->user();
        $isRegistered = $webinar->isRegistered($student);
        $meetingUrl = $webinar->getMeetingUrlForStudent($student);

        $requiresPayment = false;
        if ($isRegistered && $webinar->price > 0) {
            $pivot = $webinar->students()
                ->where('student_id', $student->id)
                ->whereNull('webinar_student.cancelled_at')
                ->first()?->pivot;

            $requiresPayment = empty($pivot?->transaction_id);
        }

        return view('student.webinar.show', compact('webinar', 'isRegistered', 'meetingUrl', 'requiresPayment'));
    }
}
