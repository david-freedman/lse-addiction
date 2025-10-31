<?php

namespace App\Domains\Shared\Casts;

use App\Domains\Shared\ValueObjects\Email;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class EmailCast implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): ?Email
    {
        if ($value === null) {
            return null;
        }

        return Email::from($value);
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): array
    {
        if ($value === null) {
            return [$key => null];
        }

        if ($value instanceof Email) {
            return [$key => $value->value];
        }

        return [$key => Email::from($value)->value];
    }
}
