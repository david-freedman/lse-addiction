<?php

namespace App\Applications\Http\Admin\Student\Controllers;

use App\Domains\Student\Actions\RestoreStudentAction;
use App\Domains\Student\Models\Student;
use Illuminate\Http\RedirectResponse;

final class RestoreStudentController
{
    public function __invoke(int $studentId): RedirectResponse
    {
        $student = Student::withTrashed()->findOrFail($studentId);

        RestoreStudentAction::execute($student);

        return redirect()
            ->route('admin.students.show', $student)
            ->with('success', 'Студента успішно відновлено');
    }
}
