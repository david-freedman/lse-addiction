<?php

namespace App\Domains\Shared\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidEmail implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value === null || $value === '') {
            return;
        }

        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $fail(__('validation.custom.email.valid_email'));
        }
    }
}
