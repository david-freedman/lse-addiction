<?php

namespace App\Domains\Quiz\Data;

use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;

class CreateQuizData extends Data
{
    public function __construct(
        #[Nullable, StringType, Max(255)]
        public readonly ?string $title = null,

        #[Min(0), Max(100)]
        public readonly int $passing_score = 70,

        #[Nullable, Min(1)]
        public readonly ?int $max_attempts = null,

        #[Nullable, Min(1)]
        public readonly ?int $time_limit_minutes = null,

        public readonly bool $show_correct_answers = true,
    ) {}
}
