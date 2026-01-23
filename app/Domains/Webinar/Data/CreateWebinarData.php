<?php

namespace App\Domains\Webinar\Data;

use App\Domains\Webinar\Enums\WebinarStatus;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Rules\Enum;
use Spatie\LaravelData\Attributes\Validation\Image;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Mimes;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\Numeric;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Attributes\Validation\Url;
use Spatie\LaravelData\Data;

class CreateWebinarData extends Data
{
    public function __construct(
        #[Required, StringType, Min(3), Max(255)]
        public readonly string $title,

        #[Nullable, StringType, Max(255)]
        public readonly ?string $slug,

        #[Nullable, StringType]
        public readonly ?string $description,

        #[Nullable, Image, Mimes(['jpeg', 'jpg', 'png', 'webp']), Max(5120)]
        public readonly ?UploadedFile $banner,

        #[Required, Numeric]
        public readonly int $teacher_id,

        #[Required]
        public readonly string $starts_at,

        #[Required, Numeric, Min(15), Max(480)]
        public readonly int $duration_minutes,

        #[Nullable, StringType, Url, Max(500)]
        public readonly ?string $meeting_url,

        #[Nullable, StringType, Url, Max(500)]
        public readonly ?string $recording_url,

        #[Required, StringType]
        public readonly string $status,

        #[Nullable, Numeric, Min(1)]
        public readonly ?int $max_participants,

        #[Required, Numeric, Min(0)]
        public readonly float $price,

        #[Nullable, Numeric, Min(0)]
        public readonly ?float $old_price,
    ) {}

    public static function rules(): array
    {
        return [
            'status' => ['required', new Enum(WebinarStatus::class)],
            'teacher_id' => ['required', 'integer', 'exists:teachers,id'],
            'starts_at' => ['required', 'date', 'date_format:d.m.Y H:i'],
            'old_price' => ['nullable', 'numeric', 'min:0', 'gte:price'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:webinars,slug'],
            'recording_url' => ['nullable', 'url', 'max:500', 'required_if:status,recorded'],
        ];
    }

    public static function messages(): array
    {
        return [
            'recording_url.required_if' => 'Посилання на запис обов\'язкове для статусу "У записі"',
        ];
    }
}
