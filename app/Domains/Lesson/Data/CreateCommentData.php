<?php

namespace App\Domains\Lesson\Data;

use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;

class CreateCommentData extends Data
{
    public function __construct(
        #[Required, StringType, Max(5000)]
        public readonly string $content,

        #[Nullable]
        public readonly ?int $parent_id = null,
    ) {}
}
