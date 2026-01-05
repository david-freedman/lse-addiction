<?php

namespace App\Domains\Course\Data;

use App\Domains\Course\Enums\CourseStatus;
use Carbon\Carbon;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Data;

class CourseFilterData extends Data
{
    public function __construct(
        #[Nullable]
        public readonly ?string $search = null,

        #[Nullable]
        public readonly ?CourseStatus $status = null,

        #[Nullable]
        public readonly ?int $teacher_id = null,

        #[Nullable, WithCast(DateTimeInterfaceCast::class, format: 'd.m.Y')]
        public readonly ?Carbon $created_from = null,

        #[Nullable, WithCast(DateTimeInterfaceCast::class, format: 'd.m.Y')]
        public readonly ?Carbon $created_to = null,
    ) {}
}
