<?php

namespace App\Domains\Shared\Casts;

use App\Domains\Shared\ValueObjects\Email;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataProperty;

class EmailDataCast implements Cast
{
    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context): ?Email
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof Email) {
            return $value;
        }

        return Email::from($value);
    }
}
