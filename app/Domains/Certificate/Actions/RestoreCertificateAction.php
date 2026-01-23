<?php

namespace App\Domains\Certificate\Actions;

use App\Domains\ActivityLog\Actions\LogActivityAction;
use App\Domains\ActivityLog\Data\ActivityLogData;
use App\Domains\ActivityLog\Enums\ActivitySubject;
use App\Domains\ActivityLog\Enums\ActivityType;
use App\Domains\Certificate\Models\Certificate;

final class RestoreCertificateAction
{
    public function __invoke(Certificate $certificate): bool
    {
        $result = $certificate->restore();

        if ($result) {
            LogActivityAction::execute(ActivityLogData::from([
                'subject_type' => ActivitySubject::Certificate,
                'subject_id' => $certificate->id,
                'activity_type' => ActivityType::CertificateRestored,
                'description' => 'Certificate restored',
                'properties' => [
                    'certificate_id' => $certificate->id,
                    'certificate_number' => $certificate->certificate_number,
                    'student_id' => $certificate->student_id,
                    'course_id' => $certificate->course_id,
                    'restored_by' => auth('admin')->id(),
                ],
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]));
        }

        return $result;
    }
}
