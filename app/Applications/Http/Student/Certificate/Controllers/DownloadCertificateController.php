<?php

namespace App\Applications\Http\Student\Certificate\Controllers;

use App\Domains\Certificate\Actions\GenerateCertificatePdfAction;
use App\Domains\Certificate\Models\Certificate;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Response;

final class DownloadCertificateController
{
    use AuthorizesRequests;

    public function __invoke(Certificate $certificate): Response
    {
        $this->authorize('download', $certificate);

        return app(GenerateCertificatePdfAction::class)($certificate);
    }
}
