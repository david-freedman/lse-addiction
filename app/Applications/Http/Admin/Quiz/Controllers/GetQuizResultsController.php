<?php

namespace App\Applications\Http\Admin\Quiz\Controllers;

use App\Domains\Quiz\Data\QuizResultsFilterData;
use App\Domains\Quiz\Models\Quiz;
use App\Domains\Quiz\ViewModels\Admin\QuizResultsViewModel;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class GetQuizResultsController
{
    public function __invoke(Request $request, Quiz $quiz): View
    {
        $quiz->load('quizzable');

        $filters = QuizResultsFilterData::from($request->all());
        $viewModel = new QuizResultsViewModel($quiz, $filters);

        $lesson = $quiz->quizzable;
        $module = $lesson->module;
        $course = $module->course;

        $breadcrumbs = [
            ['title' => 'Курси', 'url' => route('admin.courses.index')],
            ['title' => $course->name, 'url' => route('admin.courses.show', $course)],
            ['title' => $module->name, 'url' => route('admin.modules.edit', [$course, $module])],
            ['title' => $lesson->name, 'url' => route('admin.lessons.edit', [$course, $module, $lesson])],
            ['title' => 'Результати'],
        ];

        return view('admin.quiz.results.index', compact('viewModel', 'breadcrumbs'));
    }
}
