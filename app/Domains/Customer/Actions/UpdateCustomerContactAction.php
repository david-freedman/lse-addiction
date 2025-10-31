<?php

namespace App\Domains\Customer\Actions;

use App\Domains\ActivityLog\Actions\LogActivityAction;
use App\Domains\ActivityLog\Data\ActivityLogData;
use App\Domains\ActivityLog\Enums\ActivitySubject;
use App\Domains\ActivityLog\Enums\ActivityType;
use App\Domains\Customer\Data\UpdateContactData;
use App\Domains\Customer\Data\VerifyCodeData;
use App\Domains\Customer\Models\Customer;

class UpdateCustomerContactAction
{
    public static function execute(Customer $customer, UpdateContactData $data, VerifyCodeData $verifyData): Customer
    {
        $verified = VerifyCodeAction::execute($verifyData);

        if (! $verified || $verified->id !== $customer->id) {
            return $customer;
        }

        $properties = [];

        if ($data->email && $verifyData->type === 'email') {
            $properties['old_email'] = $customer->email->value;
            $properties['new_email'] = $data->email->value;
            $customer->email = $data->email;
            $customer->markEmailAsVerified();
        }

        if ($data->phone && $verifyData->type === 'phone') {
            $properties['old_phone'] = $customer->phone->value;
            $properties['new_phone'] = $data->phone->value;
            $customer->phone = $data->phone;
            $customer->markPhoneAsVerified();
        }

        $customer->save();

        LogActivityAction::execute(ActivityLogData::from([
            'subject_type' => ActivitySubject::Customer,
            'subject_id' => $customer->id,
            'activity_type' => ActivityType::CustomerContactChanged,
            'description' => 'Customer contact information updated',
            'properties' => $properties,
            'ip_address' => null,
            'user_agent' => null,
        ]));

        return $customer;
    }
}
