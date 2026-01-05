<?php

namespace App\Applications\Http\Admin\Certificate\Controllers;

use App\Domains\Certificate\Actions\RestoreCertificateAction;
use App\Domains\Certificate\Models\Certificate;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;

final class RestoreCertificateController
{
    use AuthorizesRequests;

    public function __invoke(int $certificateId): RedirectResponse
    {
        $certificate = Certificate::withTrashed()->findOrFail($certificateId);

        $this->authorize('restore', $certificate);

        app(RestoreCertificateAction::class)($certificate);

        return redirect()->back()
            ->with('success', 'Сертифікат успішно відновлено');
    }
}
