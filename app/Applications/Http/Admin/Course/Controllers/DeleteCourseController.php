<?php

namespace App\Applications\Http\Admin\Course\Controllers;

use App\Domains\Course\Actions\DeleteCourseAction;
use App\Domains\Course\Models\Course;
use Illuminate\Http\RedirectResponse;

final class DeleteCourseController
{
    public function __invoke(Course $course): RedirectResponse
    {
        DeleteCourseAction::execute($course);

        return redirect()
            ->route('admin.courses.index')
            ->with('success', 'Курс успішно видалено');
    }
}
