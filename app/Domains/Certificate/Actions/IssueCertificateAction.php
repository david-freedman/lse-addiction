<?php

namespace App\Domains\Certificate\Actions;

use App\Domains\ActivityLog\Actions\LogActivityAction;
use App\Domains\ActivityLog\Data\ActivityLogData;
use App\Domains\ActivityLog\Enums\ActivitySubject;
use App\Domains\ActivityLog\Enums\ActivityType;
use App\Domains\Certificate\Models\Certificate;
use App\Domains\Course\Models\Course;
use App\Domains\Student\Models\Student;
use App\Notifications\CertificateIssuedNotification;

final class IssueCertificateAction
{
    public function __invoke(Student $student, Course $course, ?int $issuedBy = null, ?float $grade = null): ?Certificate
    {
        if (Certificate::where('student_id', $student->id)
            ->where('course_id', $course->id)
            ->exists()) {
            return null;
        }

        $calculatedGrade = $grade ?? app(CalculateCourseGradeAction::class)($student, $course);
        $studyHours = $course->total_duration;
        $autoPublish = !$course->requiresCertificateApproval();

        $certificate = Certificate::create([
            'certificate_number' => Certificate::generateNumber($course, $student),
            'student_id' => $student->id,
            'course_id' => $course->id,
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
            'description' => 'Certificate issued',
            'properties' => [
                'certificate_number' => $certificate->certificate_number,
                'student_id' => $student->id,
                'student_email' => $student->email->value,
                'course_id' => $course->id,
                'course_name' => $course->name,
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
