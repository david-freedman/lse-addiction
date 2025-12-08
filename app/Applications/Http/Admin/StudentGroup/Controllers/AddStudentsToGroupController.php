<?php

namespace App\Applications\Http\Admin\StudentGroup\Controllers;

use App\Domains\Student\Actions\AddStudentsToGroupAction;
use App\Domains\Student\Data\AddStudentsToGroupData;
use App\Domains\Student\Models\StudentGroup;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class AddStudentsToGroupController
{
    public function __invoke(Request $request, StudentGroup $studentGroup): RedirectResponse
    {
        $data = AddStudentsToGroupData::validateAndCreate($request->all());

        AddStudentsToGroupAction::execute($studentGroup, $data);

        return redirect()
            ->route('admin.student-groups.show', $studentGroup)
            ->with('success', 'Студентів успішно додано до групи');
    }
}
