<?php

namespace App\Applications\Http\Student\Webinar\Controllers;

use App\Domains\Webinar\Models\Webinar;
use Illuminate\View\View;

final class ShowWebinarController
{
    public function __invoke(Webinar $webinar): View
    {
        $webinar->load('teacher');

        $student = auth()->user();
        $isRegistered = $webinar->isRegistered($student);
        $meetingUrl = $webinar->getMeetingUrlForStudent($student);

        return view('student.webinar.show', compact('webinar', 'isRegistered', 'meetingUrl'));
    }
}
