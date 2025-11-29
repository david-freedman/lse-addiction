<?php

namespace App\Applications\Http\Student\Course\Controllers;

use App\Domains\Course\Actions\EnrollStudentAction;
use App\Domains\Course\Models\Course;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class EnrollCourseController
{
    public function __invoke(Request $request, Course $course): RedirectResponse
    {
        $student = $request->user();

        if ($course->status !== 'published') {
            return redirect()
                ->back()
                ->with('error', 'Цей курс недоступний для запису');
        }

        EnrollStudentAction::execute($course, $student);

        return redirect()
            ->route('student.my-courses')
            ->with('success', 'Ви успішно записалися на курс');
    }
}
