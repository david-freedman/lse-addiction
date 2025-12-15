<?php

namespace App\Domains\Certificate\Actions;

use App\Domains\Certificate\Models\Certificate;

final class RestoreCertificateAction
{
    public function __invoke(Certificate $certificate): bool
    {
        return $certificate->restore();
    }
}
