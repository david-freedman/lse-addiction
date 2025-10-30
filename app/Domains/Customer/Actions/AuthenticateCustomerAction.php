<?php

namespace App\Domains\Customer\Actions;

use App\Domains\Customer\Models\Customer;
use Illuminate\Support\Facades\Auth;

class AuthenticateCustomerAction
{
    public static function execute(Customer $customer): void
    {
        Auth::guard('web')->login($customer);
        request()->session()->regenerate();
    }
}
