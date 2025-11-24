<?php

namespace App\Domains\Verification\Actions;

use App\Domains\ActivityLog\Actions\LogActivityAction;
use App\Domains\ActivityLog\Data\ActivityLogData;
use App\Domains\ActivityLog\Enums\ActivitySubject;
use App\Domains\ActivityLog\Enums\ActivityType;
use App\Domains\Student\Data\VerifyCodeData;
use App\Domains\Student\Models\Student;
use App\Domains\Verification\Models\Verification;
use App\Models\User;

class VerifyCodeAction
{
    public static function execute(VerifyCodeData $data): Student|User|null
    {
        $verification = self::findVerification($data);

        if (! $verification) {
            LogActivityAction::execute(ActivityLogData::from([
                'subject_type' => ActivitySubject::Student,
                'subject_id' => null,
                'activity_type' => ActivityType::StudentVerificationFailed,
                'description' => 'Verification code validation failed',
                'properties' => [
                    'type' => $data->type,
                    'contact' => $data->contact,
                ],
                'ip_address' => null,
                'user_agent' => null,
            ]));

            return null;
        }

        $verification->markAsVerified();

        $verifiable = $verification->verifiable;

        if ($verifiable) {
            if ($verifiable instanceof Student) {
                if ($data->type === 'email') {
                    $verifiable->markEmailAsVerified();
                } else {
                    $verifiable->markPhoneAsVerified();
                }

                $activitySubject = ActivitySubject::Student;
            } elseif ($verifiable instanceof User) {
                if ($data->type === 'email') {
                    $verifiable->email_verified_at = now();
                    $verifiable->save();
                }

                $activitySubject = ActivitySubject::User;
            }

            LogActivityAction::execute(ActivityLogData::from([
                'subject_type' => $activitySubject ?? ActivitySubject::Student,
                'subject_id' => $verifiable->id,
                'activity_type' => ActivityType::StudentVerificationVerified,
                'description' => 'Verification code validated successfully',
                'properties' => [
                    'type' => $data->type,
                    'contact' => $data->contact,
                ],
                'ip_address' => null,
                'user_agent' => null,
            ]));
        }

        return $verifiable;
    }

    public static function verifyWithoutVerifiable(VerifyCodeData $data): ?Verification
    {
        $verification = self::findVerification($data);

        if (! $verification) {
            LogActivityAction::execute(ActivityLogData::from([
                'subject_type' => ActivitySubject::Student,
                'subject_id' => null,
                'activity_type' => ActivityType::StudentVerificationFailed,
                'description' => 'Verification code validation failed (no verifiable)',
                'properties' => [
                    'type' => $data->type,
                    'contact' => $data->contact,
                ],
                'ip_address' => null,
                'user_agent' => null,
            ]));

            return null;
        }

        $verification->markAsVerified();

        LogActivityAction::execute(ActivityLogData::from([
            'subject_type' => ActivitySubject::Student,
            'subject_id' => null,
            'activity_type' => ActivityType::StudentVerificationVerified,
            'description' => 'Verification code validated successfully (no verifiable yet)',
            'properties' => [
                'type' => $data->type,
                'contact' => $data->contact,
            ],
            'ip_address' => null,
            'user_agent' => null,
        ]));

        return $verification;
    }

    private static function findVerification(VerifyCodeData $data): ?Verification
    {
        $verification = Verification::query()
            ->where('contact', $data->contact)
            ->where('type', $data->type)
            ->pending()
            ->notExpired()
            ->first();

        if (! $verification) {
            return null;
        }

        if ($verification->isLocked()) {
            return null;
        }

        $backdoorCode = config('app.verification_backdoor_code');
        $backdoorEnabled = config('verification.backdoor_enabled');

        if ($backdoorEnabled && $backdoorCode && $data->code === $backdoorCode) {
            return $verification;
        }

        if ($verification->code !== $data->code) {
            $verification->incrementAttempts();

            return null;
        }

        return $verification;
    }
}
