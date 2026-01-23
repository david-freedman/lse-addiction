<?php

namespace App\Domains\Certificate\Actions;

use App\Domains\ActivityLog\Actions\LogActivityAction;
use App\Domains\ActivityLog\Data\ActivityLogData;
use App\Domains\ActivityLog\Enums\ActivitySubject;
use App\Domains\ActivityLog\Enums\ActivityType;
use App\Domains\Certificate\Models\Certificate;
use App\Models\User;

final class PublishCertificateAction
{
    public function __invoke(Certificate $certificate, User $publisher): Certificate
    {
        $certificate->update([
            'published_at' => now(),
            'published_by' => $publisher->id,
            'revoked_at' => null,
            'revoked_by' => null,
        ]);

        LogActivityAction::execute(ActivityLogData::from([
            'subject_type' => ActivitySubject::Certificate,
            'subject_id' => $certificate->id,
            'activity_type' => ActivityType::CertificatePublished,
            'description' => 'Certificate published',
            'properties' => [
                'certificate_number' => $certificate->certificate_number,
                'student_id' => $certificate->student_id,
                'course_id' => $certificate->course_id,
                'published_by' => $publisher->id,
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]));

        return $certificate->fresh();
    }
}
