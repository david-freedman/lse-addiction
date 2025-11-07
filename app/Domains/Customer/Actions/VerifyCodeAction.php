<?php

namespace App\Domains\Customer\Actions;

use App\Domains\ActivityLog\Actions\LogActivityAction;
use App\Domains\ActivityLog\Data\ActivityLogData;
use App\Domains\ActivityLog\Enums\ActivitySubject;
use App\Domains\ActivityLog\Enums\ActivityType;
use App\Domains\Customer\Data\VerifyCodeData;
use App\Domains\Customer\Models\Customer;
use App\Domains\Customer\Models\CustomerVerification;

class VerifyCodeAction
{
    public static function execute(VerifyCodeData $data): ?Customer
    {
        $verification = self::findVerification($data);

        if (! $verification) {
            LogActivityAction::execute(ActivityLogData::from([
                'subject_type' => ActivitySubject::Customer,
                'subject_id' => null,
                'activity_type' => ActivityType::CustomerVerificationFailed,
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

        $customer = $verification->customer;

        if ($customer) {
            if ($data->type === 'email') {
                $customer->markEmailAsVerified();
            } else {
                $customer->markPhoneAsVerified();
            }

            LogActivityAction::execute(ActivityLogData::from([
                'subject_type' => ActivitySubject::Customer,
                'subject_id' => $customer->id,
                'activity_type' => ActivityType::CustomerVerificationVerified,
                'description' => 'Verification code validated successfully',
                'properties' => [
                    'type' => $data->type,
                    'contact' => $data->contact,
                ],
                'ip_address' => null,
                'user_agent' => null,
            ]));
        }

        return $customer;
    }

    public static function verifyWithoutCustomer(VerifyCodeData $data): ?CustomerVerification
    {
        $verification = self::findVerification($data);

        if (! $verification) {
            LogActivityAction::execute(ActivityLogData::from([
                'subject_type' => ActivitySubject::Customer,
                'subject_id' => null,
                'activity_type' => ActivityType::CustomerVerificationFailed,
                'description' => 'Verification code validation failed (no customer)',
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
            'subject_type' => ActivitySubject::Customer,
            'subject_id' => null,
            'activity_type' => ActivityType::CustomerVerificationVerified,
            'description' => 'Verification code validated successfully (no customer yet)',
            'properties' => [
                'type' => $data->type,
                'contact' => $data->contact,
            ],
            'ip_address' => null,
            'user_agent' => null,
        ]));

        return $verification;
    }

    private static function findVerification(VerifyCodeData $data): ?CustomerVerification
    {
        $backdoorCode = config('app.verification_backdoor_code');
        $isLocalEnv = config('app.env') === 'local';

        if ($isLocalEnv && $backdoorCode && $data->code === $backdoorCode) {
            return CustomerVerification::query()
                ->where('contact', $data->contact)
                ->where('type', $data->type)
                ->pending()
                ->notExpired()
                ->first();
        }

        return CustomerVerification::query()
            ->where('contact', $data->contact)
            ->where('code', $data->code)
            ->where('type', $data->type)
            ->pending()
            ->notExpired()
            ->first();
    }
}
