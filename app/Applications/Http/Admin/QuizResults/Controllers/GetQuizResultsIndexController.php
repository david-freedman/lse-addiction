<?php

namespace App\Applications\Http\Admin\QuizResults\Controllers;

use App\Domains\Quiz\Data\QuizResultsIndexFilterData;
use App\Domains\Quiz\ViewModels\Admin\QuizResultsIndexViewModel;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class GetQuizResultsIndexController
{
    public function __invoke(Request $request): View
    {
        $filters = QuizResultsIndexFilterData::from([
            'tab' => $request->string('tab')->toString() ?: 'quizzes',
            'status' => $request->string('status')->toString() ?: null,
            'course_id' => $request->integer('course_id') ?: null,
            'module_id' => $request->integer('module_id') ?: null,
            'lesson_id' => $request->integer('lesson_id') ?: null,
            'quiz_id' => $request->integer('quiz_id') ?: null,
            'search' => $request->string('search')->toString() ?: null,
        ]);

        $user = $request->user('admin');
        $restrictToCourseIds = $user->isTeacher() ? $user->getTeacherCourseIds() : null;

        $viewModel = new QuizResultsIndexViewModel($filters, $restrictToCourseIds);

        return view('admin.quiz-results.index', compact('viewModel'));
    }
}
