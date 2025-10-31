<?php

namespace App\Domains\Customer\Actions;

use App\Domains\ActivityLog\Actions\LogActivityAction;
use App\Domains\ActivityLog\Data\ActivityLogData;
use App\Domains\ActivityLog\Enums\ActivitySubject;
use App\Domains\ActivityLog\Enums\ActivityType;
use App\Domains\Customer\Exceptions\VerificationRateLimitException;
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
        $existingVerification = CustomerVerification::query()
            ->where('contact', $contact)
            ->where('type', $type)
            ->where('purpose', $purpose)
            ->pending()
            ->notExpired()
            ->latest('created_at')
            ->first();

        if ($existingVerification) {
            if (!$existingVerification->canResend()) {
                throw new VerificationRateLimitException(
                    secondsUntilResend: $existingVerification->getSecondsUntilResend(),
                    reason: 'interval'
                );
            }

            if ($existingVerification->isHourlyLimitReached()) {
                throw new VerificationRateLimitException(
                    secondsUntilResend: $existingVerification->hourly_reset_at->diffInSeconds(now()),
                    reason: 'hourly_limit'
                );
            }

            $existingVerification->incrementSendCount();
            $verification = $existingVerification;
        } else {
            $verification = CustomerVerification::generate(
                customerId: $customerId,
                type: $type,
                contact: $contact,
                purpose: $purpose
            );
            $verification->last_sent_at = now();
            $verification->send_count = 1;
            $verification->hourly_reset_at = now()->addHour();
            $verification->save();
        }

        if ($type === 'email') {
            self::sendEmail($contact, $verification->code);
        } else {
            self::sendPhone($contact, $verification->code);
        }

        LogActivityAction::execute(ActivityLogData::from([
            'subject_type' => ActivitySubject::Customer,
            'subject_id' => $customerId,
            'activity_type' => ActivityType::CustomerVerificationSent,
            'description' => 'Verification code sent',
            'properties' => [
                'type' => $type,
                'contact' => $contact,
                'purpose' => $purpose,
            ],
            'ip_address' => null,
            'user_agent' => null,
        ]));

        return $verification;
    }

    private static function sendEmail(string $email, string $code): void
    {
        $url = config('services.sendpulse.event_url');

        Http::post($url, [
            'email' => $email,
            'reg_code' => $code,
        ]);
    }

    private static function sendPhone(string $phone, string $code): void
    {
        $url = config('services.sendpulse.event_url');

        Http::post($url, [
            'phone' => $phone,
            'reg_code' => $code,
        ]);
    }
}
