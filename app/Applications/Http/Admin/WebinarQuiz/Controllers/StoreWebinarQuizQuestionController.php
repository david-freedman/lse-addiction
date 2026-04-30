<?php

namespace App\Applications\Http\Admin\WebinarQuiz\Controllers;

use App\Domains\Quiz\Actions\CreateQuestionAction;
use App\Domains\Quiz\Data\CreateQuestionData;
use App\Domains\Webinar\Models\Webinar;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class StoreWebinarQuizQuestionController
{
    public function __invoke(Request $request, Webinar $webinar): RedirectResponse
    {
        $data = CreateQuestionData::validateAndCreate($request->all());

        CreateQuestionAction::execute($webinar->quiz, $data);

        return redirect()
            ->route('admin.webinar-quiz.questions.index', $webinar)
            ->with('success', 'Питання успішно створено');
    }
}
