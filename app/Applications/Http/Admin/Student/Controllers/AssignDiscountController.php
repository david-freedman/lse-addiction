<?php

namespace App\Applications\Http\Admin\Student\Controllers;

use App\Domains\Discount\Actions\AssignDiscountAction;
use App\Domains\Discount\Data\AssignDiscountData;
use App\Domains\Student\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class AssignDiscountController
{
    public function __invoke(Request $request, Student $student): RedirectResponse
    {
        $data = AssignDiscountData::validateAndCreate($request->all());

        if ($student->courses()->where('course_id', $data->course_id)->exists()) {
            return redirect()
                ->route('admin.students.show', $student)
                ->with('error', 'Студент вже записаний на цей курс');
        }

        AssignDiscountAction::execute($student, $data);

        return redirect()
            ->route('admin.students.show', $student)
            ->with('success', 'Знижку успішно призначено');
    }
}
