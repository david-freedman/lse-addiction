<?php

namespace App\Domains\Certificate\Actions;

use App\Domains\ActivityLog\Actions\LogActivityAction;
use App\Domains\ActivityLog\Data\ActivityLogData;
use App\Domains\ActivityLog\Enums\ActivitySubject;
use App\Domains\ActivityLog\Enums\ActivityType;
use App\Domains\Certificate\Models\Certificate;
use App\Models\User;

final class RevokeCertificateAction
{
    public function __invoke(Certificate $certificate, User $revoker): Certificate
    {
        $certificate->update([
            'revoked_at' => now(),
            'revoked_by' => $revoker->id,
        ]);

        LogActivityAction::execute(ActivityLogData::from([
            'subject_type' => ActivitySubject::Certificate,
            'subject_id' => $certificate->id,
            'activity_type' => ActivityType::CertificateRevoked,
            'description' => 'Certificate revoked',
            'properties' => [
                'certificate_number' => $certificate->certificate_number,
                'student_id' => $certificate->student_id,
                'course_id' => $certificate->course_id,
                'revoked_by' => $revoker->id,
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]));

        return $certificate->fresh();
    }
}
