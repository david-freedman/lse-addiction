<?php

namespace App\Domains\Shared\Casts;

use App\Domains\Shared\ValueObjects\Phone;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataProperty;

class PhoneDataCast implements Cast
{
    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context): ?Phone
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof Phone) {
            return $value;
        }

        return Phone::from($value);
    }
}
