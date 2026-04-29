<?php

namespace App\Domains\Certificate\Actions;

use App\Domains\ActivityLog\Actions\LogActivityAction;
use App\Domains\ActivityLog\Data\ActivityLogData;
use App\Domains\ActivityLog\Enums\ActivitySubject;
use App\Domains\ActivityLog\Enums\ActivityType;
use App\Domains\Certificate\Models\Certificate;
use App\Domains\Student\Models\Student;
use App\Domains\Webinar\Models\Webinar;
use App\Notifications\CertificateIssuedNotification;

final class IssueWebinarCertificateAction
{
    public function __invoke(Student $student, Webinar $webinar, ?int $issuedBy = null, ?float $grade = null): ?Certificate
    {
        if (Certificate::where('student_id', $student->id)
            ->where('webinar_id', $webinar->id)
            ->exists()) {
            return null;
        }

        $calculatedGrade = $grade ?? 100.0;
        $studyHours = ($webinar->cert_bpr_hours ?? 0) * 60;
        $autoPublish = !$webinar->requiresCertificateApproval();

        $certificate = Certificate::create([
            'certificate_number' => Certificate::generateNumber($webinar, $student),
            'student_id' => $student->id,
            'webinar_id' => $webinar->id,
            'grade' => $calculatedGrade,
            'study_hours' => $studyHours,
            'issued_at' => now(),
            'issued_by' => $issuedBy,
            'published_at' => $autoPublish ? now() : null,
            'published_by' => $autoPublish ? $issuedBy : null,
        ]);

        LogActivityAction::execute(ActivityLogData::from([
            'subject_type' => ActivitySubject::Certificate,
            'subject_id' => $certificate->id,
            'activity_type' => ActivityType::CertificateIssued,
            'description' => 'Webinar certificate issued',
            'properties' => [
                'certificate_number' => $certificate->certificate_number,
                'student_id' => $student->id,
                'student_email' => $student->email->value,
                'webinar_id' => $webinar->id,
                'webinar_title' => $webinar->title,
                'grade' => $calculatedGrade,
                'study_hours' => $studyHours,
                'issued_by' => $issuedBy,
                'auto_published' => $autoPublish,
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]));

        if ($autoPublish) {
            $student->notify(new CertificateIssuedNotification($certificate));
        }

        return $certificate;
    }
}
