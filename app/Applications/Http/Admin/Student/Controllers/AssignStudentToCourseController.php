<?php

namespace App\Applications\Http\Admin\Student\Controllers;

use App\Domains\Student\Actions\AssignStudentToCoursesAction;
use App\Domains\Student\Data\AssignToCourseData;
use App\Domains\Student\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class AssignStudentToCourseController
{
    public function __invoke(Request $request, Student $student): RedirectResponse
    {
        $assignments = [];

        if ($request->has('courses')) {
            foreach ($request->input('courses', []) as $courseData) {
                $assignments[] = AssignToCourseData::validateAndCreate($courseData);
            }
        }

        AssignStudentToCoursesAction::execute($student, $assignments);

        return redirect()
            ->route('admin.students.show', $student)
            ->with('success', 'Студента успішно призначено на курси');
    }
}
