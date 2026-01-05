<?php

namespace App\Applications\Http\Admin\Certificate\Controllers;

use App\Domains\Certificate\Data\CertificateFilterData;
use App\Domains\Certificate\ViewModels\Admin\CertificateListViewModel;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class GetCertificatesController
{
    public function __invoke(Request $request): View
    {
        $filters = CertificateFilterData::from($request->all());
        $viewModel = new CertificateListViewModel(
            filters: $filters,
            perPage: 20,
        );

        return view('admin.certificates.index', compact('viewModel'));
    }
}
