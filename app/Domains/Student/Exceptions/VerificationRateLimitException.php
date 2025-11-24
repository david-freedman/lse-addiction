<?php

namespace App\Domains\Student\Exceptions;

use Exception;

class VerificationRateLimitException extends Exception
{
    public function __construct(
        public readonly int $secondsUntilResend,
        public readonly string $reason = 'interval'
    ) {
        $minutes = ceil($this->secondsUntilResend / 60);
        $message = $this->reason === 'hourly_limit'
            ? __('messages.verification.hourly_limit_reached')
            : __('messages.verification.resend_too_soon', ['seconds' => $this->secondsUntilResend, 'minutes' => $minutes]);

        parent::__construct($message);
    }
}
