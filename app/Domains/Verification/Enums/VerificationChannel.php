<?php

declare(strict_types=1);

namespace App\Domains\Verification\Enums;

enum VerificationChannel: string
{
    case EMAIL = 'email';
    case PHONE = 'phone';
}
