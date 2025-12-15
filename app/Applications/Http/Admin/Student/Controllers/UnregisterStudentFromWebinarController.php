<?php

namespace App\Applications\Http\Admin\Student\Controllers;

use App\Domains\Student\Actions\UnregisterStudentFromWebinarAction;
use App\Domains\Student\Models\Student;
use App\Domains\Webinar\Models\Webinar;
use Illuminate\Http\RedirectResponse;

final class UnregisterStudentFromWebinarController
{
    public function __invoke(Student $student, Webinar $webinar): RedirectResponse
    {
        UnregisterStudentFromWebinarAction::execute($student, $webinar);

        return redirect()
            ->route('admin.students.show', $student)
            ->with('success', 'Студента відписано від вебінару');
    }
}
