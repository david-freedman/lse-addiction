<?php

namespace App\Domains\Progress\Data;

use Carbon\Carbon;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Data;

class ProgressFilterData extends Data
{
    public function __construct(
        #[Nullable]
        public readonly ?int $course_id = null,

        #[Nullable]
        public readonly ?int $group_id = null,

        #[Nullable, WithCast(DateTimeInterfaceCast::class, format: 'd.m.Y')]
        public readonly ?Carbon $date_from = null,

        #[Nullable, WithCast(DateTimeInterfaceCast::class, format: 'd.m.Y')]
        public readonly ?Carbon $date_to = null,
    ) {}
}
