<?php

declare(strict_types=1);

namespace App\Domains\Verification\Actions;

use App\Domains\ActivityLog\Actions\LogActivityAction;
use App\Domains\ActivityLog\Data\ActivityLogData;
use App\Domains\ActivityLog\Enums\ActivitySubject;
use App\Domains\ActivityLog\Enums\ActivityType;
use App\Domains\Student\Exceptions\VerificationRateLimitException;
use App\Domains\Verification\Factories\VerificationProviderFactory;
use App\Domains\Verification\Models\Verification;
use Illuminate\Support\Facades\Log;

class SendVerificationCodeAction
{
    public static function execute(
        string $verifiableType,
        ?int $verifiableId,
        string $type,
        string $contact,
        string $purpose
    ): Verification {
        $existingVerification = Verification::query()
            ->where('verifiable_type', $verifiableType)
            ->where('contact', $contact)
            ->where('type', $type)
            ->where('purpose', $purpose)
            ->pending()
            ->notExpired()
            ->latest('created_at')
            ->first();

        if ($existingVerification) {
            if (! $existingVerification->canResend()) {
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
            $verification = Verification::generate(
                verifiableType: $verifiableType,
                verifiableId: $verifiableId,
                type: $type,
                contact: $contact,
                purpose: $purpose
            );
            $verification->last_sent_at = now();
            $verification->send_count = 1;
            $verification->hourly_reset_at = now()->addHour();
            $verification->save();
        }

        $requireVerification = config("verification.require_{$type}", true);

        if ($requireVerification) {
            $provider = VerificationProviderFactory::make($type);
            $result = SendWithRetryAction::execute($provider, $contact, $verification->code);

            if (! $result->success) {
                Log::error('Failed to send verification code', [
                    'type' => $type,
                    'contact' => $contact,
                    'purpose' => $purpose,
                    'error' => $result->message,
                ]);
            }

            if ($result->success && $result->generatedCode) {
                $verification->code = $result->generatedCode;
                $verification->save();
            }
        } else {
            $result = (object) [
                'success' => true,
                'message' => 'Verification sending disabled',
                'externalId' => null,
                'generatedCode' => null,
            ];
        }

        $activitySubject = $verifiableType === 'App\Domains\Student\Models\Student'
            ? ActivitySubject::Student
            : ActivitySubject::User;

        LogActivityAction::execute(ActivityLogData::from([
            'subject_type' => $activitySubject,
            'subject_id' => $verifiableId,
            'activity_type' => ActivityType::StudentVerificationSent,
            'description' => 'Verification code sent',
            'properties' => [
                'type' => $type,
                'contact' => $contact,
                'purpose' => $purpose,
                'success' => $result->success,
                'external_id' => $result->externalId,
            ],
            'ip_address' => null,
            'user_agent' => null,
        ]));

        return $verification;
    }
}
