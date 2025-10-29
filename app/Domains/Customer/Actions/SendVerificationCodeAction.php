<?php

namespace App\Domains\Customer\Actions;

use App\Domains\Customer\Models\CustomerVerification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendVerificationCodeAction
{
    public static function execute(
        string $type,
        string $contact,
        string $purpose,
        ?int $customerId = null
    ): CustomerVerification {
        $verification = CustomerVerification::generate(
            customerId: $customerId,
            type: $type,
            contact: $contact,
            purpose: $purpose
        );

        if ($type === 'email') {
            self::sendEmail($contact, $verification->code);
        } else {
            self::sendPhone($contact, $verification->code);
        }

        return $verification;
    }

    private static function sendEmail(string $email, string $code): void
    {
        $url = config('services.sendpulse.event_url');

        Http::post($url, [
            'email' => $email,
            'reg_code' => $code,
        ]);

        Log::info("Verification code sent to email: {$email}, code: {$code}");
    }

    private static function sendPhone(string $phone, string $code): void
    {
        Log::info("Phone verification stub: {$phone}, code: {$code}");
    }
}
