<?php

namespace App\Domains\Shared\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidPhone implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value === null || $value === '') {
            return;
        }

        if (! preg_match('/^\+380\d{9}$/', $value)) {
            $fail(__('validation.custom.phone.valid_phone'));
        }
    }
}
