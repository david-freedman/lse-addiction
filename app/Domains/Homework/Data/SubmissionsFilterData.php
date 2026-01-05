<?php

namespace App\Domains\Homework\Data;

use App\Domains\Homework\Enums\HomeworkSubmissionStatus;
use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;

class SubmissionsFilterData extends Data
{
    public function __construct(
        #[Nullable, StringType]
        public readonly ?string $status = null,

        #[Nullable, IntegerType]
        public readonly ?int $course_id = null,

        #[Nullable, IntegerType]
        public readonly ?int $module_id = null,

        #[Nullable, IntegerType]
        public readonly ?int $lesson_id = null,

        #[Nullable, StringType, Max(255)]
        public readonly ?string $search = null,
    ) {}

    public function getStatusEnum(): ?HomeworkSubmissionStatus
    {
        if ($this->status === null) {
            return null;
        }

        return HomeworkSubmissionStatus::tryFrom($this->status);
    }

    public function isReviewedTab(): bool
    {
        return $this->status === 'reviewed';
    }
}
