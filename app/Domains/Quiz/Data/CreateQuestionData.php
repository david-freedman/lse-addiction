<?php

namespace App\Domains\Quiz\Data;

use App\Domains\Quiz\Enums\QuestionType;
use Illuminate\Http\UploadedFile;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;

class CreateQuestionData extends Data
{
    public function __construct(
        #[Required]
        public readonly QuestionType $type,

        #[Required, Max(65535)]
        public readonly string $question_text,

        #[Nullable]
        public readonly ?UploadedFile $question_image = null,

        #[Min(1)]
        public readonly int $points = 1,

        #[Required]
        public readonly array $answers = [],
    ) {}
}
