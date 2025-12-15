<?php

namespace App\Applications\Http\Admin\Quiz\Controllers;

use App\Domains\Course\Models\Course;
use App\Domains\Lesson\Models\Lesson;
use App\Domains\Module\Models\Module;
use App\Domains\Quiz\Actions\DeleteQuestionAction;
use App\Domains\Quiz\Models\QuizQuestion;
use Illuminate\Http\RedirectResponse;

final class DeleteQuestionController
{
    public function __invoke(Course $course, Module $module, Lesson $lesson, QuizQuestion $question): RedirectResponse
    {
        DeleteQuestionAction::execute($question);

        return redirect()
            ->route('admin.quiz.questions.index', [$course, $module, $lesson])
            ->with('success', 'Питання успішно видалено');
    }
}
