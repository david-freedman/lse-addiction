<?php

namespace App\Applications\Http\Admin\Student\Controllers;

use App\Domains\Student\Actions\UpdateStudentAction;
use App\Domains\Student\Data\UpdateStudentData;
use App\Domains\Student\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class UpdateStudentController
{
    public function __invoke(Request $request, Student $student): RedirectResponse
    {
        $data = UpdateStudentData::validateAndCreate(
            array_merge($request->all(), ['studentId' => $student->id])
        );

        UpdateStudentAction::execute($student, $data);

        return redirect()
            ->route('admin.students.show', $student)
            ->with('success', 'Дані студента успішно оновлено');
    }
}
