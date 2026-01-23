<?php

namespace App\Applications\Http\Admin\Certificate\Controllers;

use App\Domains\Certificate\Actions\PublishCertificatesBatchAction;
use App\Domains\Certificate\Data\PublishCertificatesData;
use App\Domains\Certificate\Models\Certificate;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class PublishCertificatesController
{
    use AuthorizesRequests;

    public function __invoke(Request $request, PublishCertificatesBatchAction $batchAction): RedirectResponse
    {
        $data = PublishCertificatesData::from($request->all());

        $certificates = Certificate::whereIn('id', $data->certificate_ids)->get();

        foreach ($certificates as $certificate) {
            $this->authorize('publish', $certificate);
        }

        $published = $batchAction($data->certificate_ids, $request->user('admin'));

        $count = $published->count();

        return redirect()->back()
            ->with('success', "Успішно опубліковано {$count} сертифікат(ів)");
    }
}
