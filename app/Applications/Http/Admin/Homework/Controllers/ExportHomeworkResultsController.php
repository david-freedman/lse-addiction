<?php

namespace App\Applications\Http\Admin\Homework\Controllers;

use App\Domains\Homework\Actions\ExportHomeworkResultsAction;
use App\Domains\Homework\Data\HomeworkListFilterData;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class ExportHomeworkResultsController
{
    public function __invoke(Request $request, ExportHomeworkResultsAction $action): StreamedResponse
    {
        $filters = HomeworkListFilterData::from([
            'course_id' => $request->integer('course_id') ?: null,
            'module_id' => $request->integer('module_id') ?: null,
            'lesson_id' => $request->integer('lesson_id') ?: null,
            'has_pending' => $request->boolean('has_pending') ?: null,
            'search' => $request->string('search')->toString() ?: null,
        ]);

        return $action->toExcel($filters);
    }
}
