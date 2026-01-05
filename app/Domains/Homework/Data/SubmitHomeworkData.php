<?php

namespace App\Domains\Homework\Data;

use Illuminate\Http\UploadedFile;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;

class SubmitHomeworkData extends Data
{
    public function __construct(
        #[Nullable, StringType, Max(50000)]
        public readonly ?string $text_response = null,

        #[Nullable]
        public readonly ?array $files = null,
    ) {}

    public function hasContent(): bool
    {
        return $this->text_response !== null || ($this->files !== null && count($this->files) > 0);
    }
}
