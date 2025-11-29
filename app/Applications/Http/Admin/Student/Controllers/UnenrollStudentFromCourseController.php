<?php

namespace App\Applications\Http\Admin\Student\Controllers;

use App\Domains\Course\Models\Course;
use App\Domains\Student\Actions\UnenrollStudentFromCourseAction;
use App\Domains\Student\Models\Student;
use Illuminate\Http\RedirectResponse;

final class UnenrollStudentFromCourseController
{
    public function __invoke(Student $student, Course $course): RedirectResponse
    {
        UnenrollStudentFromCourseAction::execute($student, $course);

        return redirect()
            ->route('admin.students.show', $student)
            ->with('success', 'Студента відписано від курсу');
    }
}
