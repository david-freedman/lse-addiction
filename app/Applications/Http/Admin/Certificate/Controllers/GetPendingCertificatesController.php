<?php

namespace App\Applications\Http\Admin\Certificate\Controllers;

use App\Domains\Certificate\Data\CertificateFilterData;
use App\Domains\Certificate\Enums\CertificateStatus;
use App\Domains\Certificate\ViewModels\Admin\CertificateListViewModel;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class GetPendingCertificatesController
{
    public function __invoke(Request $request): View
    {
        $user = $request->user('admin');
        $restrictToCourseIds = $user->isTeacher() ? $user->getTeacherCourseIds() : null;

        $filters = CertificateFilterData::from([
            ...$request->all(),
            'status' => CertificateStatus::Pending->value,
        ]);

        $viewModel = new CertificateListViewModel(
            filters: $filters,
            perPage: 20,
            restrictToCourseIds: $restrictToCourseIds,
        );

        return view('admin.certificates.pending', compact('viewModel'));
    }
}
