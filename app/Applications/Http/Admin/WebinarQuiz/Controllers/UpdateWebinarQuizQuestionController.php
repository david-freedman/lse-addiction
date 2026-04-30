<?php

namespace App\Applications\Http\Admin\WebinarQuiz\Controllers;

use App\Domains\Quiz\Actions\UpdateQuestionAction;
use App\Domains\Quiz\Data\UpdateQuestionData;
use App\Domains\Quiz\Models\QuizQuestion;
use App\Domains\Webinar\Models\Webinar;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class UpdateWebinarQuizQuestionController
{
    public function __invoke(Request $request, Webinar $webinar, QuizQuestion $question): RedirectResponse
    {
        $data = UpdateQuestionData::validateAndCreate($request->all());

        UpdateQuestionAction::execute($question, $data);

        return redirect()
            ->route('admin.webinar-quiz.questions.index', $webinar)
            ->with('success', 'Питання успішно оновлено');
    }
}
