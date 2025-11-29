<?php

namespace App\Applications\Http\Admin\Course\Controllers;

use App\Domains\Course\Actions\CreateCourseAction;
use App\Domains\Course\Data\CreateCourseData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class StoreCourseController
{
    public function __invoke(Request $request): RedirectResponse
    {
        $data = CreateCourseData::validateAndCreate($request->all());

        $course = CreateCourseAction::execute($data);

        return redirect()
            ->route('admin.courses.show', $course)
            ->with('success', 'Курс успішно створено');
    }
}
