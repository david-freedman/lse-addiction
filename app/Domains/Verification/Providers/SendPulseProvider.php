<?php

declare(strict_types=1);

namespace App\Domains\Verification\Providers;

use App\Domains\Verification\Contracts\VerificationProviderInterface;
use App\Domains\Verification\Data\SendVerificationResult;
use App\Domains\Verification\Enums\VerificationChannel;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendPulseProvider implements VerificationProviderInterface
{
    public function __construct(
        private readonly string $eventUrl,
    ) {}

    public function send(string $contact, string $code): SendVerificationResult
    {
        try {
            $response = Http::post($this->eventUrl, [
                'email' => $contact,
                'reg_code' => $code,
            ]);

            if ($response->successful()) {
                return SendVerificationResult::success(
                    metadata: ['response' => $response->json()]
                );
            }

            Log::warning('SendPulse verification failed', [
                'email' => $contact,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return SendVerificationResult::failure(
                message: 'Failed to send verification email',
                metadata: [
                    'status' => $response->status(),
                    'response' => $response->body(),
                ]
            );
        } catch (\Throwable $e) {
            Log::error('SendPulse verification exception', [
                'email' => $contact,
                'error' => $e->getMessage(),
            ]);

            return SendVerificationResult::failure(
                message: 'Exception occurred while sending verification email: '.$e->getMessage(),
                metadata: ['exception' => get_class($e)]
            );
        }
    }

    public function supports(string $channel): bool
    {
        return $channel === VerificationChannel::EMAIL->value;
    }
}
