<?php

declare(strict_types=1);

namespace App\Domains\Verification\Providers;

use App\Domains\Verification\Contracts\VerificationProviderInterface;
use App\Domains\Verification\Data\SendVerificationResult;
use Illuminate\Support\Facades\Log;

class NullProvider implements VerificationProviderInterface
{
    public function send(string $contact, string $code): SendVerificationResult
    {
        Log::info('NullProvider: verification code not sent (testing mode)', [
            'contact' => $contact,
            'code' => $code,
        ]);

        return SendVerificationResult::success(
            metadata: [
                'provider' => 'null',
                'code' => $code,
                'contact' => $contact,
            ]
        );
    }

    public function supports(string $channel): bool
    {
        return true;
    }
}
