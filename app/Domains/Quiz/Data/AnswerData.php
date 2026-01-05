<?php

namespace App\Domains\Quiz\Data;

use Illuminate\Http\UploadedFile;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Data;

class AnswerData extends Data
{
    public function __construct(
        #[Nullable]
        public readonly ?int $id = null,

        #[Nullable, Max(65535)]
        public readonly ?string $answer_text = null,

        #[Nullable]
        public readonly ?UploadedFile $answer_image = null,

        #[Nullable]
        public readonly ?string $existing_image = null,

        public readonly bool $is_correct = false,

        #[Nullable, Max(255)]
        public readonly ?string $category = null,

        public readonly int $order = 0,

        #[Nullable]
        public readonly ?int $correct_order = null,
    ) {}
}
