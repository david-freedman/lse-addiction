<?php

namespace App\Applications\Http\Admin\WebinarQuiz\Controllers;

use App\Domains\Quiz\Enums\QuestionType;
use App\Domains\Webinar\Models\Webinar;
use Illuminate\View\View;

final class CreateWebinarQuizQuestionController
{
    public function __invoke(Webinar $webinar): View
    {
        $questionTypes = QuestionType::cases();

        $breadcrumbs = [
            ['title' => 'Вебінари', 'url' => route('admin.webinars.index')],
            ['title' => $webinar->title, 'url' => route('admin.webinars.edit', $webinar)],
            ['title' => 'Питання тесту', 'url' => route('admin.webinar-quiz.questions.index', $webinar)],
            ['title' => 'Створити'],
        ];

        return view('admin.webinar-quiz.questions.create', compact('webinar', 'questionTypes', 'breadcrumbs'));
    }
}
