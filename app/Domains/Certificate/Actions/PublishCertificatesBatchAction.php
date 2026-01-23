<?php

namespace App\Domains\Certificate\Actions;

use App\Domains\Certificate\Models\Certificate;
use App\Models\User;
use App\Notifications\CertificatePublishedNotification;
use Illuminate\Support\Collection;

final class PublishCertificatesBatchAction
{
    public function __construct(
        private PublishCertificateAction $publishAction
    ) {}

    public function __invoke(array $certificateIds, User $publisher): Collection
    {
        $certificates = Certificate::whereIn('id', $certificateIds)
            ->pending()
            ->with(['student', 'course'])
            ->get();

        $published = collect();

        foreach ($certificates as $certificate) {
            $publishedCertificate = ($this->publishAction)($certificate, $publisher);
            $published->push($publishedCertificate);

            $certificate->student->notify(new CertificatePublishedNotification($publishedCertificate));
        }

        return $published;
    }
}
