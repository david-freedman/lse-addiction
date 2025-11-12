<?php

namespace App\Domains\Course\Data;

use App\Domains\Course\Enums\CourseStatus;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Rules\Enum;
use Spatie\LaravelData\Attributes\Validation\Date;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Attributes\Validation\Image;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\Mimes;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\Numeric;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;

class UpdateCourseData extends Data
{
    public function __construct(
        #[Required, StringType, Min(3), Max(255)]
        public readonly string $name,

        #[Required, StringType]
        public readonly string $description,

        #[Required, Numeric, Min(0)]
        public readonly float $price,

        #[Required, Numeric]
        public readonly int $coach_id,

        #[Nullable, Image, Mimes(['jpeg', 'jpg', 'png', 'webp']), Max(5120)]
        public readonly ?UploadedFile $banner,

        #[Required, StringType, Max(50)]
        public readonly string $status,

        #[Nullable]
        public readonly ?array $tags,

        #[Nullable, StringType, Max(50)]
        public readonly ?string $type,

        #[Nullable, Date]
        public readonly ?string $starts_at,

        #[Nullable, StringType, Max(50)]
        public readonly ?string $label,

        #[Nullable, Numeric, Exists('users', 'id')]
        public readonly ?int $author_id,
    ) {}

    public static function rules(): array
    {
        return [
            'status' => ['required', new Enum(CourseStatus::class)],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['string', 'max:50'],
            'type' => ['nullable', 'string', 'in:upcoming,recorded,free'],
            'starts_at' => ['nullable', 'date'],
        ];
    }
}
