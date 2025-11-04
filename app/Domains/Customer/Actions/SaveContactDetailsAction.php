<?php

namespace App\Domains\Customer\Actions;

use App\Domains\ActivityLog\Actions\LogActivityAction;
use App\Domains\ActivityLog\Data\ActivityLogData;
use App\Domains\ActivityLog\Enums\ActivitySubject;
use App\Domains\ActivityLog\Enums\ActivityType;
use App\Domains\Customer\Data\ContactDetailsData;
use App\Domains\Customer\Models\Customer;

class SaveContactDetailsAction
{
    public static function execute(Customer $customer, ContactDetailsData $data): Customer
    {
        $customer->update([
            'name' => $data->name,
            'surname' => $data->surname,
            'birthday' => $data->birthday,
            'city' => $data->city,
        ]);

        LogActivityAction::execute(ActivityLogData::from([
            'subject_type' => ActivitySubject::Customer,
            'subject_id' => $customer->id,
            'activity_type' => ActivityType::CustomerContactDetailsAdded,
            'description' => 'Customer contact details saved during registration',
            'properties' => [
                'name' => $data->name,
                'surname' => $data->surname,
                'city' => $data->city,
            ],
            'ip_address' => null,
            'user_agent' => null,
        ]));

        return $customer->fresh();
    }
}
