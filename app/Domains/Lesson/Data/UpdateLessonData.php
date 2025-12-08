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

class UpdateLessonData extends Data
{
    public function __construct(
        #[Required, StringType, Max(255)]
        public readonly string $name,

        #[Nullable, StringType]
        public readonly ?string $description = null,

        #[Nullable, StringType]
        public readonly ?string $content = null,

        #[Nullable]
        public readonly ?LessonType $type = null,

        #[Nullable, Url]
        public readonly ?string $video_url = null,

        #[Nullable, StringType]
        public readonly ?string $dicom_file = null,

        #[Nullable]
        public readonly ?int $duration_minutes = null,

        #[Nullable]
        public readonly ?int $order = null,

        #[Nullable]
        public readonly ?LessonStatus $status = null,

        #[Nullable]
        public readonly ?bool $is_downloadable = null,

        #[Nullable]
        public readonly ?array $attachments = null,
    ) {}
}
