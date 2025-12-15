<?php

namespace App\Applications\Http\Admin\Course\Controllers;

use App\Domains\Course\Actions\RestoreCourseAction;
use App\Domains\Course\Models\Course;
use Illuminate\Http\RedirectResponse;

final class RestoreCourseController
{
    public function __invoke(Course $course): RedirectResponse
    {
        RestoreCourseAction::execute($course);

        return redirect()
            ->route('admin.courses.index')
            ->with('success', 'Курс успішно відновлено');
    }
}
