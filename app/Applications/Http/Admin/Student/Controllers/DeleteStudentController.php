<?php

namespace App\Applications\Http\Admin\Student\Controllers;

use App\Domains\Student\Actions\DeleteStudentAction;
use App\Domains\Student\Models\Student;
use Illuminate\Http\RedirectResponse;

final class DeleteStudentController
{
    public function __invoke(Student $student): RedirectResponse
    {
        DeleteStudentAction::execute($student);

        return redirect()
            ->route('admin.students.index')
            ->with('success', 'Студента успішно видалено');
    }
}
