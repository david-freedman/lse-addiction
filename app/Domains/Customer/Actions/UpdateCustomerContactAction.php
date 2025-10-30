<?php

namespace App\Domains\Customer\Actions;

use App\Domains\Customer\Data\UpdateContactData;
use App\Domains\Customer\Data\VerifyCodeData;
use App\Domains\Customer\Models\Customer;

class UpdateCustomerContactAction
{
    public static function execute(Customer $customer, UpdateContactData $data, VerifyCodeData $verifyData): Customer
    {
        $verified = VerifyCodeAction::execute($verifyData);

        if (!$verified || $verified->id !== $customer->id) {
            return $customer;
        }

        if ($data->email && $verifyData->type === 'email') {
            $customer->email = $data->email;
            $customer->markEmailAsVerified();
        }

        if ($data->phone && $verifyData->type === 'phone') {
            $customer->phone = $data->phone;
            $customer->markPhoneAsVerified();
        }

        $customer->save();

        return $customer;
    }
}
