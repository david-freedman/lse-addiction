<?php

namespace App\Applications\Http\Admin\QuizResults\Controllers;

use App\Domains\Quiz\Actions\ExportQuizResultsAction;
use App\Domains\Quiz\Data\QuizResultsIndexFilterData;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class ExportQuizResultsController
{
    public function __invoke(Request $request, ExportQuizResultsAction $action): StreamedResponse
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

        return $action->toExcel($filters, $restrictToCourseIds);
    }
}
