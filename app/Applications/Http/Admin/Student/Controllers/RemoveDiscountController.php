<?php

namespace App\Applications\Http\Admin\Student\Controllers;

use App\Domains\Discount\Actions\RemoveDiscountAction;
use App\Domains\Discount\Models\StudentCourseDiscount;
use App\Domains\Student\Models\Student;
use Illuminate\Http\RedirectResponse;

final class RemoveDiscountController
{
    public function __invoke(Student $student, StudentCourseDiscount $discount): RedirectResponse
    {
        if ($discount->student_id !== $student->id) {
            abort(404);
        }

        if (! $discount->isActive()) {
            return redirect()
                ->route('admin.students.show', $student)
                ->with('error', 'Ця знижка вже використана');
        }

        RemoveDiscountAction::execute($discount);

        return redirect()
            ->route('admin.students.show', $student)
            ->with('success', 'Знижку успішно видалено');
    }
}
