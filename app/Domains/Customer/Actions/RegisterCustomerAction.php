<?php

namespace App\Domains\Customer\Actions;

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
            contact: $customer->phone,
            purpose: 'registration',
            customerId: $customer->id
        );

        SendVerificationCodeAction::execute(
            type: 'email',
            contact: $customer->email,
            purpose: 'registration',
            customerId: $customer->id
        );

        return $customer;
    }
}
