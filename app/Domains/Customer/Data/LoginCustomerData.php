<?php

namespace App\Domains\Customer\Data;

use App\Domains\Shared\Rules\EmailOrPhone;
use App\Domains\Shared\ValueObjects\Email;
use App\Domains\Shared\ValueObjects\Phone;
use Spatie\LaravelData\Data;

class LoginCustomerData extends Data
{
    public function __construct(
        public readonly string $contact,
    ) {}

    public static function rules(): array
    {
        return [
            'contact' => ['required', 'string', new EmailOrPhone],
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

    public function getEmail(): Email
    {
        return Email::from($this->contact);
    }

    public function getPhone(): Phone
    {
        return Phone::from($this->contact);
    }
}
