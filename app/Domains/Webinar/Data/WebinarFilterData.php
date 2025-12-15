<?php

namespace App\Domains\Webinar\Data;

use App\Domains\Webinar\Enums\WebinarStatus;
use Carbon\Carbon;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Data;

class WebinarFilterData extends Data
{
    public function __construct(
        #[Nullable]
        public readonly ?string $search = null,

        #[Nullable]
        public readonly ?WebinarStatus $status = null,

        #[Nullable]
        public readonly ?int $teacher_id = null,

        #[Nullable, WithCast(DateTimeInterfaceCast::class, format: 'Y-m-d')]
        public readonly ?Carbon $date_from = null,

        #[Nullable, WithCast(DateTimeInterfaceCast::class, format: 'Y-m-d')]
        public readonly ?Carbon $date_to = null,
    ) {}
}
