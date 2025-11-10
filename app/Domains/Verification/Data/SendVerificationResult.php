<?php

declare(strict_types=1);

namespace App\Domains\Verification\Data;

use Spatie\LaravelData\Data;

class SendVerificationResult extends Data
{
    public function __construct(
        public bool $success,
        public ?string $message = null,
        public ?string $externalId = null,
        public ?string $generatedCode = null,
        public ?array $metadata = null,
    ) {}

    public static function success(?string $externalId = null, ?string $generatedCode = null, ?array $metadata = null): self
    {
        return new self(
            success: true,
            message: 'Verification code sent successfully',
            externalId: $externalId,
            generatedCode: $generatedCode,
            metadata: $metadata,
        );
    }

    public static function failure(string $message, ?array $metadata = null): self
    {
        return new self(
            success: false,
            message: $message,
            externalId: null,
            generatedCode: null,
            metadata: $metadata,
        );
    }
}
