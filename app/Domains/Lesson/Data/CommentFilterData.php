<?php

namespace App\Domains\Lesson\Data;

use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;

class CommentFilterData extends Data
{
    public function __construct(
        #[Nullable, IntegerType]
        public readonly ?int $course_id = null,

        #[Nullable, IntegerType]
        public readonly ?int $lesson_id = null,

        #[Nullable, StringType, Max(255)]
        public readonly ?string $search = null,
    ) {}

    public function isFiltered(): bool
    {
        return $this->course_id !== null
            || $this->lesson_id !== null
            || $this->search !== null;
    }
}
