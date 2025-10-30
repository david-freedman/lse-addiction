<?php

namespace App\Domains\Customer\Data;

use Spatie\LaravelData\Data;

class UpdateContactData extends Data
{
    public function __construct(
        public readonly ?string $email,
        public readonly ?string $phone,
    ) {}

    public static function rules(): array
    {
        return [
            'email' => ['nullable', 'email', 'unique:customers,email'],
            'phone' => ['nullable', 'string', 'unique:customers,phone'],
        ];
    }
}
