<?php

namespace App\Applications\Http\Admin\WebinarQuiz\Controllers;

use App\Domains\Quiz\Actions\DeleteQuestionAction;
use App\Domains\Quiz\Models\QuizQuestion;
use App\Domains\Webinar\Models\Webinar;
use Illuminate\Http\RedirectResponse;

final class DeleteWebinarQuizQuestionController
{
    public function __invoke(Webinar $webinar, QuizQuestion $question): RedirectResponse
    {
        DeleteQuestionAction::execute($question);

        return redirect()
            ->route('admin.webinar-quiz.questions.index', $webinar)
            ->with('success', 'Питання успішно видалено');
    }
}
