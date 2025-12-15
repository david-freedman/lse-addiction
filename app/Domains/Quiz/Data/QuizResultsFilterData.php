<?php

namespace App\Domains\Quiz\Data;

use Carbon\Carbon;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Data;

class QuizResultsFilterData extends Data
{
    public function __construct(
        #[Nullable, StringType, Max(255)]
        public readonly ?string $search = null,

        #[Nullable]
        public readonly ?bool $passed = null,

        #[Nullable, WithCast(DateTimeInterfaceCast::class, format: 'd.m.Y')]
        public readonly ?Carbon $date_from = null,

        #[Nullable, WithCast(DateTimeInterfaceCast::class, format: 'd.m.Y')]
        public readonly ?Carbon $date_to = null,
    ) {}

    public function isFiltered(): bool
    {
        return $this->search !== null
            || $this->passed !== null
            || $this->date_from !== null
            || $this->date_to !== null;
    }
}
