<?php

namespace App\Applications\Http\Admin\Student\Controllers;

use App\Domains\Student\Models\Student;
use Illuminate\Http\RedirectResponse;

final class ForceDeleteStudentController
{
    public function __invoke(int $studentId): RedirectResponse
    {
        $student = Student::withTrashed()->findOrFail($studentId);

        $student->forceDelete();

        return redirect()
            ->route('admin.students.index')
            ->with('success', 'Студента повністю видалено');
    }
}
