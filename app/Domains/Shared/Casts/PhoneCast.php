<?php

namespace App\Domains\Shared\Casts;

use App\Domains\Shared\ValueObjects\Phone;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class PhoneCast implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): ?Phone
    {
        if ($value === null) {
            return null;
        }

        return Phone::from($value);
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): array
    {
        if ($value === null) {
            return [$key => null];
        }

        if ($value instanceof Phone) {
            return [$key => $value->value];
        }

        return [$key => Phone::from($value)->value];
    }
}
