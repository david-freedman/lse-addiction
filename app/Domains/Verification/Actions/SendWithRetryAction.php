<?php

declare(strict_types=1);

namespace App\Domains\Verification\Actions;

use App\Domains\Verification\Contracts\VerificationProviderInterface;
use App\Domains\Verification\Data\SendVerificationResult;
use Illuminate\Support\Facades\Log;

class SendWithRetryAction
{
    private const MAX_ATTEMPTS = 3;

    private const INITIAL_DELAY_MS = 100;

    public static function execute(
        VerificationProviderInterface $provider,
        string $contact,
        string $code,
        int $maxAttempts = self::MAX_ATTEMPTS,
    ): SendVerificationResult {
        $attempt = 1;
        $lastResult = null;

        while ($attempt <= $maxAttempts) {
            $result = $provider->send($contact, $code);

            if ($result->success) {
                if ($attempt > 1) {
                    Log::info('Verification sent successfully after retry', [
                        'contact' => $contact,
                        'attempt' => $attempt,
                    ]);
                }

                return $result;
            }

            $lastResult = $result;

            if ($attempt < $maxAttempts) {
                $delayMs = self::INITIAL_DELAY_MS * (2 ** ($attempt - 1));

                Log::warning('Verification send failed, retrying', [
                    'contact' => $contact,
                    'attempt' => $attempt,
                    'max_attempts' => $maxAttempts,
                    'delay_ms' => $delayMs,
                    'error' => $result->message,
                ]);

                usleep($delayMs * 1000);
            }

            $attempt++;
        }

        Log::error('Verification send failed after all retries', [
            'contact' => $contact,
            'attempts' => $maxAttempts,
            'last_error' => $lastResult?->message,
        ]);

        return $lastResult ?? SendVerificationResult::failure('Failed to send verification after all retries');
    }
}
