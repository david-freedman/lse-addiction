<?php

namespace App\Domains\Customer\Actions;

use App\Domains\ActivityLog\Actions\LogActivityAction;
use App\Domains\ActivityLog\Data\ActivityLogData;
use App\Domains\ActivityLog\Enums\ActivitySubject;
use App\Domains\ActivityLog\Enums\ActivityType;
use App\Domains\Customer\Data\RegisterCustomerData;
use App\Domains\Customer\Models\Customer;

class RegisterCustomerAction
{
    public static function execute(RegisterCustomerData $data): Customer
    {
        $customer = Customer::create([
            'email' => $data->email,
            'phone' => $data->phone,
        ]);

        SendVerificationCodeAction::execute(
            type: 'phone',
            contact: $customer->phone->value,
            purpose: 'registration',
            customerId: $customer->id
        );

        SendVerificationCodeAction::execute(
            type: 'email',
            contact: $customer->email->value,
            purpose: 'registration',
            customerId: $customer->id
        );

        LogActivityAction::execute(ActivityLogData::from([
            'subject_type' => ActivitySubject::Customer,
            'subject_id' => $customer->id,
            'activity_type' => ActivityType::CustomerRegistered,
            'description' => 'Customer registered successfully',
            'properties' => [
                'email' => $customer->email->value,
                'phone' => $customer->phone->value,
            ],
            'ip_address' => null,
            'user_agent' => null,
        ]));

        return $customer;
    }
}
