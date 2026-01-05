<?php

namespace App\Applications\Http\Admin\StudentGroup\Controllers;

use App\Domains\Student\Actions\CreateStudentGroupAction;
use App\Domains\Student\Data\CreateStudentGroupData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class StoreStudentGroupController
{
    public function __invoke(Request $request): RedirectResponse
    {
        $data = CreateStudentGroupData::validateAndCreate($request->all());

        $group = CreateStudentGroupAction::execute($data, $request->user('admin'));

        return redirect()
            ->route('admin.student-groups.show', $group)
            ->with('success', 'Групу успішно створено');
    }
}
