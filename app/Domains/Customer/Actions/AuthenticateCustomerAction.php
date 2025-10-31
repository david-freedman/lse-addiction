<?php

namespace App\Domains\Customer\Actions;

use App\Domains\ActivityLog\Actions\LogActivityAction;
use App\Domains\ActivityLog\Data\ActivityLogData;
use App\Domains\ActivityLog\Enums\ActivitySubject;
use App\Domains\ActivityLog\Enums\ActivityType;
use App\Domains\Customer\Models\Customer;
use Illuminate\Support\Facades\Auth;

class AuthenticateCustomerAction
{
    public static function execute(Customer $customer): void
    {
        Auth::guard('web')->login($customer);
        request()->session()->regenerate();

        LogActivityAction::execute(ActivityLogData::from([
            'subject_type' => ActivitySubject::Customer,
            'subject_id' => $customer->id,
            'activity_type' => ActivityType::CustomerLoginSuccess,
            'description' => 'Customer logged in successfully',
            'properties' => [
                'email' => $customer->email->value,
            ],
            'ip_address' => null,
            'user_agent' => null,
        ]));
    }
}
