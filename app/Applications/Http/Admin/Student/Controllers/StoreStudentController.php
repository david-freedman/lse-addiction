<?php

namespace App\Applications\Http\Admin\Student\Controllers;

use App\Domains\Student\Actions\CreateStudentAction;
use App\Domains\Student\Data\CreateStudentData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class StoreStudentController
{
    public function __invoke(Request $request): RedirectResponse
    {
        $data = CreateStudentData::validateAndCreate($request->all());

        $student = CreateStudentAction::execute($data);

        return redirect()
            ->route('admin.students.show', $student)
            ->with('success', 'Студента успішно створено');
    }
}
