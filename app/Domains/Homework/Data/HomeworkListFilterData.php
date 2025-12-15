<?php

namespace App\Domains\Homework\Data;

use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;

class HomeworkListFilterData extends Data
{
    public function __construct(
        #[Nullable, IntegerType]
        public readonly ?int $course_id = null,

        #[Nullable, IntegerType]
        public readonly ?int $module_id = null,

        #[Nullable, IntegerType]
        public readonly ?int $lesson_id = null,

        #[Nullable]
        public readonly ?bool $has_pending = null,

        #[Nullable, StringType, Max(255)]
        public readonly ?string $search = null,
    ) {}
}
