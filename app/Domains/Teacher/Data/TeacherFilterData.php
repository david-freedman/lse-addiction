<?php

namespace App\Domains\Teacher\Data;

use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;

class TeacherFilterData extends Data
{
    public function __construct(
        #[Nullable, StringType, Max(100)]
        public readonly ?string $search = null,
        #[Nullable, StringType, Max(255)]
        public readonly ?string $specialization = null,
        #[Nullable, StringType, Max(255)]
        public readonly ?string $workplace = null,
    ) {}
}
