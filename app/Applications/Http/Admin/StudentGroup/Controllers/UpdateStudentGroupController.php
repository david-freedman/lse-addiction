<?php

namespace App\Applications\Http\Admin\StudentGroup\Controllers;

use App\Domains\Student\Actions\UpdateStudentGroupAction;
use App\Domains\Student\Data\UpdateStudentGroupData;
use App\Domains\Student\Models\StudentGroup;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class UpdateStudentGroupController
{
    public function __invoke(Request $request, StudentGroup $studentGroup): RedirectResponse
    {
        $data = UpdateStudentGroupData::validateAndCreate($request->all());

        UpdateStudentGroupAction::execute($studentGroup, $data);

        return redirect()
            ->route('admin.student-groups.show', $studentGroup)
            ->with('success', 'Групу успішно оновлено');
    }
}
