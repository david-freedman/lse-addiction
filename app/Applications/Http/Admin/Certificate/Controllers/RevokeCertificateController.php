<?php

namespace App\Applications\Http\Admin\Certificate\Controllers;

use App\Domains\Certificate\Actions\RevokeCertificateAction;
use App\Domains\Certificate\Models\Certificate;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;

final class RevokeCertificateController
{
    use AuthorizesRequests;

    public function __invoke(Certificate $certificate): RedirectResponse
    {
        $this->authorize('revoke', $certificate);

        app(RevokeCertificateAction::class)($certificate);

        return redirect()->back()
            ->with('success', 'Сертифікат успішно відкликано');
    }
}
