<?php

namespace App\Domains\Customer\Data;

use Spatie\LaravelData\Data;

class LoginCustomerData extends Data
{
    public function __construct(
        public readonly string $contact,
    ) {}

    public static function rules(): array
    {
        return [
            'contact' => ['required', 'string'],
        ];
    }

    public function isEmail(): bool
    {
        return filter_var($this->contact, FILTER_VALIDATE_EMAIL) !== false;
    }

    public function isPhone(): bool
    {
        return !$this->isEmail();
    }
}
