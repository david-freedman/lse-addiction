<?php

namespace App\Applications\Http\Admin\WebinarQuiz\Controllers;

use App\Domains\Quiz\ViewModels\Admin\WebinarQuizQuestionsViewModel;
use App\Domains\Webinar\Models\Webinar;
use Illuminate\View\View;

final class GetWebinarQuizQuestionsController
{
    public function __invoke(Webinar $webinar): View
    {
        $webinar->load('quiz.questions.answers');

        $viewModel = new WebinarQuizQuestionsViewModel($webinar);

        $breadcrumbs = [
            ['title' => 'Вебінари', 'url' => route('admin.webinars.index')],
            ['title' => $webinar->title, 'url' => route('admin.webinars.edit', $webinar)],
            ['title' => 'Питання тесту'],
        ];

        return view('admin.webinar-quiz.questions.index', compact('viewModel', 'breadcrumbs'));
    }
}
