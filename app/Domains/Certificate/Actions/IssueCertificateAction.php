<?php

namespace App\Domains\Certificate\Actions;

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

        $certificate = Certificate::create([
            'certificate_number' => Certificate::generateNumber(),
            'student_id' => $student->id,
            'course_id' => $course->id,
            'grade' => $calculatedGrade,
            'study_hours' => $studyHours,
            'issued_at' => now(),
            'issued_by' => $issuedBy,
        ]);

        $student->notify(new CertificateIssuedNotification($certificate));

        return $certificate;
    }
}
