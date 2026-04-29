<?php

namespace App\Domains\Webinar\Data;

use App\Domains\Webinar\Enums\WebinarStatus;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Rule;
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

class UpdateWebinarData extends Data
{
    public function __construct(
        #[Required, StringType, Min(3), Max(255)]
        public readonly string $title,

        #[Required, StringType]
        public readonly string $number,

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

        public readonly ?int $webinar_id = null,

        public readonly bool $sync_to_wp = false,

        #[Nullable, StringType, Max(255)]
        public readonly ?string $cert_company_name = null,

        #[Nullable, Image, Mimes(['jpeg', 'jpg', 'png']), Max(2048)]
        public readonly ?UploadedFile $cert_signature = null,

        #[Nullable, Image, Mimes(['jpeg', 'jpg', 'png']), Max(2048)]
        public readonly ?UploadedFile $cert_stamp = null,

        #[Nullable, Numeric, Min(1)]
        public readonly ?int $cert_bpr_hours = null,

        #[Nullable, StringType, Max(500)]
        public readonly ?string $cert_specialties = null,

        #[Nullable, StringType, Max(20)]
        public readonly ?string $cert_participant_type = null,

        public readonly bool $has_quiz = false,

        #[Nullable]
        public readonly ?int $quiz_passing_score = null,

        #[Nullable]
        public readonly ?int $quiz_max_attempts = null,

        #[Nullable]
        public readonly ?int $quiz_time_limit_minutes = null,

        public readonly bool $quiz_show_correct_answers = true,
    ) {}

    public static function rules(): array
    {
        return [
            'number' => ['required', 'digits:7', Rule::unique('webinars', 'number')->ignore(request()->route('webinar')?->id)],
            'status' => ['required', new Enum(WebinarStatus::class)],
            'teacher_id' => ['required', 'integer', 'exists:teachers,id'],
            'starts_at' => ['required', 'date', 'date_format:d.m.Y H:i'],
            'old_price' => ['nullable', 'numeric', 'min:0', 'gte:price'],
            'recording_url' => ['nullable', 'url', 'max:500', 'required_if:status,recorded'],
            'cert_participant_type' => ['nullable', 'string', 'in:trainer,student'],
            'cert_bpr_hours' => ['nullable', 'integer', 'min:1'],
        ];
    }

    public static function messages(): array
    {
        return [
            'recording_url.required_if' => 'Посилання на запис обов\'язкове для статусу "У записі"',
        ];
    }
}
