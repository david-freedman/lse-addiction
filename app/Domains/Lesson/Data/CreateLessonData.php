<?php

namespace App\Domains\Lesson\Data;

use App\Domains\Lesson\Enums\LessonStatus;
use App\Domains\Lesson\Enums\LessonType;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Attributes\Validation\Url;
use Spatie\LaravelData\Data;

class CreateLessonData extends Data
{
    public function __construct(
        #[Required, StringType, Max(255)]
        public readonly string $name,

        #[Nullable, StringType]
        public readonly ?string $description = null,

        #[Nullable, StringType]
        public readonly ?string $content = null,

        public readonly LessonType $type = LessonType::Text,

        #[Nullable, Url]
        public readonly ?string $video_url = null,

        #[Nullable, StringType]
        public readonly ?string $dicom_file = null,

        #[Nullable]
        public readonly ?int $duration_minutes = null,

        public readonly int $order = 0,

        public readonly LessonStatus $status = LessonStatus::Draft,

        public readonly bool $is_downloadable = false,

        #[Nullable]
        public readonly ?array $attachments = null,
    ) {}
}
