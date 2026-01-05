<?php

namespace App\Applications\Http\Admin\StudentGroup\Controllers;

use App\Domains\Student\Actions\RemoveStudentFromGroupAction;
use App\Domains\Student\Models\Student;
use App\Domains\Student\Models\StudentGroup;
use Illuminate\Http\RedirectResponse;

final class RemoveStudentFromGroupController
{
    public function __invoke(StudentGroup $studentGroup, Student $student): RedirectResponse
    {
        RemoveStudentFromGroupAction::execute($studentGroup, $student);

        return redirect()
            ->route('admin.student-groups.show', $studentGroup)
            ->with('success', 'Студента видалено з групи');
    }
}
