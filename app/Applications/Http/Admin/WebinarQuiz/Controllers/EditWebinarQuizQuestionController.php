<?php

namespace App\Applications\Http\Admin\WebinarQuiz\Controllers;

use App\Domains\Quiz\Enums\QuestionType;
use App\Domains\Quiz\Models\QuizQuestion;
use App\Domains\Webinar\Models\Webinar;
use Illuminate\View\View;

final class EditWebinarQuizQuestionController
{
    public function __invoke(Webinar $webinar, QuizQuestion $question): View
    {
        $question->load('answers');
        $questionTypes = QuestionType::cases();

        $breadcrumbs = [
            ['title' => 'Вебінари', 'url' => route('admin.webinars.index')],
            ['title' => $webinar->title, 'url' => route('admin.webinars.edit', $webinar)],
            ['title' => 'Питання тесту', 'url' => route('admin.webinar-quiz.questions.index', $webinar)],
            ['title' => 'Редагувати'],
        ];

        return view('admin.webinar-quiz.questions.edit', compact('webinar', 'question', 'questionTypes', 'breadcrumbs'));
    }
}
