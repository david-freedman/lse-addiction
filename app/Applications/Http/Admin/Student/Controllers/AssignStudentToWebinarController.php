<?php

namespace App\Applications\Http\Admin\Student\Controllers;

use App\Domains\Student\Actions\AssignStudentToWebinarAction;
use App\Domains\Student\Models\Student;
use App\Domains\Webinar\Models\Webinar;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class AssignStudentToWebinarController
{
    public function __invoke(Request $request, Student $student): RedirectResponse
    {
        $request->validate([
            'webinar_id' => ['required', 'exists:webinars,id'],
        ]);

        $webinar = Webinar::findOrFail($request->input('webinar_id'));

        $assigned = AssignStudentToWebinarAction::execute($student, $webinar);

        if (!$assigned) {
            return redirect()
                ->route('admin.students.show', $student)
                ->with('error', 'Студент вже записаний на цей вебінар');
        }

        return redirect()
            ->route('admin.students.show', $student)
            ->with('success', 'Студента успішно записано на вебінар');
    }
}
