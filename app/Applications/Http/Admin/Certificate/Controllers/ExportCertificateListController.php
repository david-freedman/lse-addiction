<?php

namespace App\Applications\Http\Admin\Certificate\Controllers;

use App\Domains\Certificate\Actions\ExportCertificateListAction;
use App\Domains\Certificate\Data\CertificateFilterData;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class ExportCertificateListController
{
    public function __invoke(Request $request, ExportCertificateListAction $action): StreamedResponse
    {
        $filters = CertificateFilterData::from([
            'search'      => $request->string('search')->toString() ?: null,
            'course_id'   => $request->integer('course_id') ?: null,
            'student_id'  => $request->integer('student_id') ?: null,
            'status'      => $request->string('status')->toString() ?: null,
            'issued_from' => $request->string('issued_from')->toString() ?: null,
            'issued_to'   => $request->string('issued_to')->toString() ?: null,
        ]);

        $certificateIds = $request->has('certificate_ids') ? $request->array('certificate_ids') : null;

        $user = $request->user('admin');
        $restrictToCourseIds = $user->isTeacher() ? $user->getTeacherCourseIds() : null;

        return $action->toExcel($filters, $restrictToCourseIds, $certificateIds);
    }
}
