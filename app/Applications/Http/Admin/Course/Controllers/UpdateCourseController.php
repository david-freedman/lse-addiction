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
        $input = $request->all();
        if ($request->boolean('tags_empty') && !isset($input['tags'])) {
            $input['tags'] = [];
        }
        $data = UpdateCourseData::validateAndCreate($input);

        UpdateCourseAction::execute($course, $data);

        return redirect()
            ->route('admin.courses.show', $course)
            ->with('success', 'Курс успішно оновлено');
    }
}
