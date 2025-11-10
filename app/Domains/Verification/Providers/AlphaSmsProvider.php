<?php

declare(strict_types=1);

namespace App\Domains\Verification\Providers;

use App\Domains\Verification\Contracts\VerificationProviderInterface;
use App\Domains\Verification\Data\SendVerificationResult;
use App\Domains\Verification\Enums\VerificationChannel;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AlphaSmsProvider implements VerificationProviderInterface
{
    public function __construct(
        private readonly string $apiKey,
        private readonly string $baseUrl = 'https://alphasms.ua/api/',
    ) {}

    public function send(string $contact, string $code): SendVerificationResult
    {
        try {
            $formattedPhone = $this->formatPhone($contact);

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl.'json.php', [
                'auth' => $this->apiKey,
                'data' => [
                    [
                        'type' => 'call/otp',
                        'id' => time(),
                        'phone' => (int) $formattedPhone,
                    ],
                ],
            ]);

            if ($response->failed()) {
                Log::warning('AlphaSMS verification request failed', [
                    'phone' => $contact,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return SendVerificationResult::failure(
                    message: 'Failed to send verification call',
                    metadata: [
                        'status' => $response->status(),
                        'response' => $response->body(),
                    ]
                );
            }

            $data = $response->json();

            if (isset($data['error']) || (isset($data['success']) && $data['success'] === false)) {
                Log::warning('AlphaSMS returned error', [
                    'phone' => $contact,
                    'error' => $data['error'] ?? 'Unknown error',
                    'response' => $data,
                ]);

                return SendVerificationResult::failure(
                    message: 'AlphaSMS error: '.($data['error'] ?? 'Unknown error'),
                    metadata: ['response' => $data]
                );
            }

            $generatedCode = $data['data'][0]['data']['code'] ?? null;

            if (! $generatedCode) {
                Log::error('AlphaSMS did not return verification code', [
                    'phone' => $contact,
                    'response' => $data,
                ]);

                return SendVerificationResult::failure(
                    message: 'AlphaSMS did not return verification code',
                    metadata: ['response' => $data]
                );
            }

            return SendVerificationResult::success(
                externalId: (string) ($data['data'][0]['id'] ?? time()),
                generatedCode: $generatedCode,
                metadata: ['response' => $data]
            );
        } catch (\Throwable $e) {
            Log::error('AlphaSMS verification exception', [
                'phone' => $contact,
                'error' => $e->getMessage(),
            ]);

            return SendVerificationResult::failure(
                message: 'Exception occurred while sending verification call: '.$e->getMessage(),
                metadata: ['exception' => get_class($e)]
            );
        }
    }

    public function supports(string $channel): bool
    {
        return $channel === VerificationChannel::PHONE->value;
    }

    private function formatPhone(string $phone): string
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);

        if (str_starts_with($phone, '380')) {
            return $phone;
        }

        if (str_starts_with($phone, '0')) {
            return '38'.$phone;
        }

        return '380'.$phone;
    }
}
