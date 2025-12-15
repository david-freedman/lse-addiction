<?php

namespace App\Applications\Http\Admin\Certificate\Controllers;

use App\Domains\Certificate\Actions\GenerateCertificatePdfAction;
use App\Domains\Certificate\Models\Certificate;
use Illuminate\Http\Response;

final class DownloadCertificateController
{
    public function __invoke(Certificate $certificate): Response
    {
        return app(GenerateCertificatePdfAction::class)($certificate);
    }
}
