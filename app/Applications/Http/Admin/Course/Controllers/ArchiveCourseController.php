<?php

namespace App\Applications\Http\Admin\Course\Controllers;

use App\Domains\Course\Actions\ArchiveCourseAction;
use App\Domains\Course\Models\Course;
use Illuminate\Http\RedirectResponse;

final class ArchiveCourseController
{
    public function __invoke(Course $course): RedirectResponse
    {
        ArchiveCourseAction::execute($course);

        return redirect()
            ->route('admin.courses.index')
            ->with('success', 'Курс успішно архівовано');
    }
}
