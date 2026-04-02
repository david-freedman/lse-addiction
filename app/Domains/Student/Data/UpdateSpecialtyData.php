<?php

namespace App\Domains\Student\Data;

use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;

class UpdateSpecialtyData extends Data
{
    public function __construct(
        #[Required, StringType, Max(255)]
        public readonly string $name,
    ) {}
}
