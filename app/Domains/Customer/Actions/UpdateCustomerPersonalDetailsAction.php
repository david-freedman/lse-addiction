<?php

namespace App\Domains\Customer\Actions;

use App\Domains\ActivityLog\Actions\LogActivityAction;
use App\Domains\ActivityLog\Data\ActivityLogData;
use App\Domains\ActivityLog\Enums\ActivitySubject;
use App\Domains\ActivityLog\Enums\ActivityType;
use App\Domains\Customer\Data\UpdatePersonalDetailsData;
use App\Domains\Customer\Models\Customer;

class UpdateCustomerPersonalDetailsAction
{
    public static function execute(Customer $customer, UpdatePersonalDetailsData $data): Customer
    {
        $customer->update([
            'surname' => $data->surname,
            'name' => $data->name,
            'birthday' => $data->birthday,
            'city' => $data->city,
        ]);

        LogActivityAction::execute(ActivityLogData::from([
            'subject_type' => ActivitySubject::Customer,
            'subject_id' => $customer->id,
            'activity_type' => ActivityType::CustomerPersonalDetailsUpdated,
            'description' => 'Customer personal details updated',
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
