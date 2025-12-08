<?php

namespace App\Domains\Student\Data;

use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\Numeric;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;

class StudentGroupFilterData extends Data
{
    public function __construct(
        #[Nullable, StringType, Max(255)]
        public readonly ?string $search,

        #[Nullable, Numeric, Exists('courses', 'id')]
        public readonly ?int $course_id,
    ) {}
}
