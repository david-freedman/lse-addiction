<?php

namespace App\Applications\Http\Admin\Certificate\Controllers;

use App\Domains\Certificate\Actions\GenerateCertificatePdfAction;
use App\Domains\Certificate\Models\Certificate;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Response;

final class PreviewCertificateController
{
    use AuthorizesRequests;

    public function __invoke(Certificate $certificate): Response
    {
        $this->authorize('preview', $certificate);

        return app(GenerateCertificatePdfAction::class)($certificate);
    }
}
