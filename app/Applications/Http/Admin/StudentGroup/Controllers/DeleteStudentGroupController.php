<?php

namespace App\Applications\Http\Admin\StudentGroup\Controllers;

use App\Domains\Student\Actions\DeleteStudentGroupAction;
use App\Domains\Student\Models\StudentGroup;
use Illuminate\Http\RedirectResponse;

final class DeleteStudentGroupController
{
    public function __invoke(StudentGroup $studentGroup): RedirectResponse
    {
        DeleteStudentGroupAction::execute($studentGroup);

        return redirect()
            ->route('admin.student-groups.index')
            ->with('success', 'Групу успішно видалено');
    }
}
