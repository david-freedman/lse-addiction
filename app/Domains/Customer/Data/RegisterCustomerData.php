<?php

namespace App\Domains\Customer\Data;

use Spatie\LaravelData\Data;

class RegisterCustomerData extends Data
{
    public function __construct(
        public readonly string $email,
        public readonly string $phone,
    ) {}

    public static function rules(): array
    {
        return [
            'email' => ['required', 'email', 'unique:customers,email'],
            'phone' => ['required', 'string', 'unique:customers,phone'],
        ];
    }
}
