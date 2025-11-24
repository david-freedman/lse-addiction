<?php

namespace App\Domains\Shared\Rules;

use App\Domains\Shared\ValueObjects\Email;
use App\Domains\Shared\ValueObjects\Phone;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use InvalidArgumentException;

class EmailOrPhone implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            Email::from($value);

            return;
        } catch (InvalidArgumentException) {
        }

        try {
            Phone::from($value);

            return;
        } catch (InvalidArgumentException) {
        }

        $fail(__('validation.custom.contact.email_or_phone'));
    }
}
