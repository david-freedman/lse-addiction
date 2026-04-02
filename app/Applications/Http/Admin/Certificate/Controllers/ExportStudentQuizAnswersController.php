<?php

namespace App\Applications\Http\Admin\Certificate\Controllers;

use App\Domains\Certificate\Actions\ExportStudentQuizAnswersAction;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class ExportStudentQuizAnswersController
{
    public function __invoke(Request $request, ExportStudentQuizAnswersAction $action): StreamedResponse
    {
        $courseId = $request->integer('course_id');

        if (! $courseId) {
            abort(400, 'Необхідно вказати course_id.');
        }

        $user = $request->user('admin');
        if ($user->isTeacher()) {
            $allowed = $user->getTeacherCourseIds();
            if (! in_array($courseId, $allowed)) {
                abort(403);
            }
        }

        return $action->toExcel($courseId);
    }
}
