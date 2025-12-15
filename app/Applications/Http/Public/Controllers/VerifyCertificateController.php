<?php

namespace App\Applications\Http\Public\Controllers;

use App\Domains\Certificate\Models\Certificate;
use Illuminate\View\View;

final class VerifyCertificateController
{
    public function __invoke(string $certificateNumber): View
    {
        $certificate = Certificate::withTrashed()
            ->with(['student', 'course.teacher'])
            ->where('certificate_number', $certificateNumber)
            ->first();

        return view('public.certificate-verify', compact('certificate'));
    }
}
