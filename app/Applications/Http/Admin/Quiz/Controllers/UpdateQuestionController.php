<?php

namespace App\Applications\Http\Admin\Quiz\Controllers;

use App\Domains\Course\Models\Course;
use App\Domains\Lesson\Models\Lesson;
use App\Domains\Module\Models\Module;
use App\Domains\Quiz\Actions\UpdateQuestionAction;
use App\Domains\Quiz\Data\UpdateQuestionData;
use App\Domains\Quiz\Models\QuizQuestion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class UpdateQuestionController
{
    public function __invoke(Request $request, Course $course, Module $module, Lesson $lesson, QuizQuestion $question): RedirectResponse
    {
        $data = UpdateQuestionData::validateAndCreate($request->all());

        UpdateQuestionAction::execute($question, $data);

        return redirect()
            ->route('admin.quiz.questions.index', [$course, $module, $lesson])
            ->with('success', 'Питання успішно оновлено');
    }
}
