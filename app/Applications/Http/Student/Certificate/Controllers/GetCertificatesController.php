<?php

namespace App\Applications\Http\Student\Certificate\Controllers;

use App\Domains\Certificate\ViewModels\CertificatesListViewModel;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class GetCertificatesController
{
    public function __invoke(Request $request): View
    {
        $student = auth()->user();
        $search = $request->query('search');

        $student->certificates()
            ->visibleToStudent()
            ->whereNull('viewed_at')
            ->update(['viewed_at' => now()]);

        $viewModel = new CertificatesListViewModel($student, $search);

        return view('student.certificates.index', compact('viewModel'));
    }
}
