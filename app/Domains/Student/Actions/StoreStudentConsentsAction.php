<?php

namespace App\Domains\Student\Actions;

use App\Domains\Student\Enums\ConsentType;
use App\Domains\Student\Models\Student;
use App\Domains\Student\Models\StudentConsent;

class StoreStudentConsentsAction
{
    public static function execute(Student $student, string $ipAddress): void
    {
        $consents = [
            ConsentType::PrivacyPolicy,
            ConsentType::PublicOffer,
        ];

        foreach ($consents as $consentType) {
            StudentConsent::updateOrCreate(
                [
                    'student_id' => $student->id,
                    'consent_type' => $consentType->value,
                ],
                [
                    'ip_address' => $ipAddress,
                    'document_version' => '1.0',
                    'consented_at' => now(),
                ]
            );
        }
    }
}
