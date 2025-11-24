<?php

namespace App\Domains\Student\Data;

use Spatie\LaravelData\Attributes\Validation\BooleanType;
use Spatie\LaravelData\Attributes\Validation\Date;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\Numeric;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;

class StudentFilterData extends Data
{
    public function __construct(
        #[Nullable, StringType, Max(255)]
        public readonly ?string $search,

        #[Nullable, Numeric, Exists('courses', 'id')]
        public readonly ?int $course_id,

        #[Nullable, BooleanType]
        public readonly ?bool $is_new,

        #[Nullable, BooleanType]
        public readonly ?bool $only_deleted,

        #[Nullable, Date]
        public readonly ?string $created_from,

        #[Nullable, Date]
        public readonly ?string $created_to,
    ) {}

    public static function rules(): array
    {
        return [
            'created_to' => ['nullable', 'date', 'after_or_equal:created_from'],
        ];
    }
}
