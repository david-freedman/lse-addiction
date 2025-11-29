<?php

namespace App\Applications\Http\Student\Course\Controllers;

use App\Domains\Course\Actions\UnenrollStudentAction;
use App\Domains\Course\Models\Course;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class UnenrollCourseController
{
    public function __invoke(Request $request, Course $course): RedirectResponse
    {
        $student = $request->user();

        if ($student->hasPurchasedCourse($course)) {
            return redirect()
                ->back()
                ->with('error', 'Неможливо відписатись від придбаного курсу');
        }

        UnenrollStudentAction::execute($course, $student);

        return redirect()
            ->route('student.my-courses')
            ->with('success', 'Ви успішно скасували запис на курс');
    }
}
