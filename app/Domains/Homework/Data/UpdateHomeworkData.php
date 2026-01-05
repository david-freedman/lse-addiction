<?php

namespace App\Domains\Homework\Data;

use App\Domains\Homework\Enums\HomeworkResponseType;
use Carbon\Carbon;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;

class UpdateHomeworkData extends Data
{
    public function __construct(
        #[Nullable, StringType]
        public readonly ?string $description = null,

        #[Nullable]
        public readonly ?HomeworkResponseType $response_type = null,

        #[Nullable, Min(1), Max(100)]
        public readonly ?int $max_points = null,

        #[Nullable, Min(0), Max(100)]
        public readonly ?int $passing_score = null,

        #[Nullable, Min(1)]
        public readonly ?int $max_attempts = null,

        #[Nullable]
        public readonly ?Carbon $deadline_at = null,

        #[Nullable]
        public readonly ?bool $is_required = null,

        #[Nullable]
        public readonly ?array $allowed_extensions = null,

        #[Nullable, Min(1), Max(50)]
        public readonly ?int $max_file_size_mb = null,

        #[Nullable, Min(1), Max(20)]
        public readonly ?int $max_files = null,
    ) {}
}
