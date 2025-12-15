<?php

namespace App\Domains\Homework\Data;

use App\Domains\Homework\Enums\HomeworkSubmissionStatus;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;

class ReviewHomeworkData extends Data
{
    public function __construct(
        #[Required]
        public readonly HomeworkSubmissionStatus $status,

        #[Nullable, Min(0)]
        public readonly ?int $score = null,

        #[Nullable, StringType, Max(5000)]
        public readonly ?string $feedback = null,
    ) {}
}
