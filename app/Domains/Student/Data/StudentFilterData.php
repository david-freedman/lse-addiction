<?php

namespace App\Domains\Student\Data;

use Carbon\Carbon;
use Spatie\LaravelData\Attributes\Validation\BooleanType;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\Numeric;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Data;

class StudentFilterData extends Data
{
    public function __construct(
        #[Nullable, StringType, Max(255)]
        public readonly ?string $search,

        #[Nullable, Numeric, Exists('courses', 'id')]
        public readonly ?int $course_id,

        #[Nullable, BooleanType]
        public readonly ?bool $is_new,

        #[Nullable, BooleanType]
        public readonly ?bool $only_deleted,

        #[Nullable, WithCast(DateTimeInterfaceCast::class, format: 'd.m.Y')]
        public readonly ?Carbon $created_from,

        #[Nullable, WithCast(DateTimeInterfaceCast::class, format: 'd.m.Y')]
        public readonly ?Carbon $created_to,
    ) {}
}
