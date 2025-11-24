<?php

declare(strict_types=1);

namespace App\Domains\Student\Actions;

use App\Domains\Student\Models\Student;
use App\Domains\Verification\Models\Verification;

class SendVerificationCodeAction
{
    public static function execute(
        string $type,
        string $contact,
        string $purpose,
        ?int $studentId = null
    ): Verification {
        return \App\Domains\Verification\Actions\SendVerificationCodeAction::execute(
            verifiableType: Student::class,
            verifiableId: $studentId,
            type: $type,
            contact: $contact,
            purpose: $purpose
        );
    }
}
