<?php

namespace App\Domains\Customer\Actions;

use App\Domains\Customer\Data\VerifyCodeData;
use App\Domains\Customer\Models\Customer;
use App\Domains\Customer\Models\CustomerVerification;

class VerifyCodeAction
{
    public static function execute(VerifyCodeData $data): ?Customer
    {
        $verification = self::findVerification($data);

        if (!$verification) {
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
        }

        return $customer;
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
