<?php

declare(strict_types=1);

namespace App\Domains\Verification\Contracts;

use App\Domains\Verification\Data\SendVerificationResult;

interface VerificationProviderInterface
{
    public function send(string $contact, string $code): SendVerificationResult;

    public function supports(string $channel): bool;
}
