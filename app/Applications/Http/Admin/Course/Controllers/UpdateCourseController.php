<?php

namespace App\Applications\Http\Admin\Course\Controllers;

use App\Domains\Course\Actions\UpdateCourseAction;
use App\Domains\Course\Data\UpdateCourseData;
use App\Domains\Course\Models\Course;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class UpdateCourseController
{
    public function __invoke(Request $request, Course $course): RedirectResponse
    {
        $data = UpdateCourseData::validateAndCreate($request->all());

        UpdateCourseAction::execute($course, $data);

        return redirect()
            ->route('admin.courses.show', $course)
            ->with('success', 'Курс успішно оновлено');
    }
}
