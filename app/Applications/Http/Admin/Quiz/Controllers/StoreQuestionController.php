<?php

namespace App\Applications\Http\Admin\Quiz\Controllers;

use App\Domains\Course\Models\Course;
use App\Domains\Lesson\Models\Lesson;
use App\Domains\Module\Models\Module;
use App\Domains\Quiz\Actions\CreateQuestionAction;
use App\Domains\Quiz\Data\CreateQuestionData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class StoreQuestionController
{
    public function __invoke(Request $request, Course $course, Module $module, Lesson $lesson): RedirectResponse
    {
        $data = CreateQuestionData::validateAndCreate($request->all());

        CreateQuestionAction::execute($lesson->quiz, $data);

        return redirect()
            ->route('admin.quiz.questions.index', [$course, $module, $lesson])
            ->with('success', 'Питання успішно створено');
    }
}
