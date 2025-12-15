<?php

namespace App\Domains\Lesson\Data;

use App\Domains\Homework\Enums\HomeworkResponseType;
use App\Domains\Lesson\Enums\DicomSourceType;
use App\Domains\Lesson\Enums\LessonStatus;
use App\Domains\Lesson\Enums\LessonType;
use Illuminate\Http\UploadedFile;
use Carbon\Carbon;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Attributes\Validation\Url;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
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

        #[Nullable]
        public readonly ?DicomSourceType $dicom_source_type = null,

        #[Nullable]
        public readonly ?UploadedFile $dicom_file_upload = null,

        #[Nullable, Url]
        public readonly ?string $dicom_url = null,

        #[Nullable, Url]
        public readonly ?string $qa_session_url = null,

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

        #[Nullable, Min(0), Max(100)]
        public readonly ?int $quiz_passing_score = null,

        #[Nullable, Min(1)]
        public readonly ?int $quiz_max_attempts = null,

        #[Nullable, Min(1)]
        public readonly ?int $quiz_time_limit_minutes = null,

        #[Nullable]
        public readonly ?bool $quiz_show_correct_answers = null,

        #[Nullable]
        public readonly ?bool $quiz_is_final = null,

        #[Nullable]
        public readonly ?bool $has_homework = null,

        #[Nullable, StringType]
        public readonly ?string $homework_description = null,

        #[Nullable]
        public readonly ?HomeworkResponseType $homework_response_type = null,

        #[Nullable, Min(1), Max(100)]
        public readonly ?int $homework_max_points = null,

        #[Nullable, Min(0), Max(100)]
        public readonly ?int $homework_passing_score = null,

        #[Nullable, Min(1)]
        public readonly ?int $homework_max_attempts = null,

        #[Nullable, WithCast(DateTimeInterfaceCast::class, format: 'Y-m-d\TH:i')]
        public readonly ?Carbon $homework_deadline_at = null,

        #[Nullable]
        public readonly ?bool $homework_is_required = null,

        #[Nullable]
        public readonly ?array $homework_allowed_extensions = null,

        #[Nullable, Min(1), Max(50)]
        public readonly ?int $homework_max_file_size_mb = null,

        #[Nullable, Min(1), Max(20)]
        public readonly ?int $homework_max_files = null,
    ) {}

    public static function rules(): array
    {
        return [
            'video_url' => ['nullable', 'url', 'required_if:type,video'],
            'dicom_source_type' => ['nullable', 'required_if:type,dicom'],
            'dicom_file_upload' => ['nullable', 'file', 'required_if:dicom_source_type,file'],
            'dicom_url' => ['nullable', 'url', 'required_if:dicom_source_type,url'],
            'qa_session_url' => ['nullable', 'url', 'required_if:type,qa_session'],
        ];
    }

    public static function messages(): array
    {
        return [
            'video_url.required_if' => 'Посилання на відео є обов\'язковим для відео-уроку.',
            'dicom_source_type.required_if' => 'Тип джерела DICOM є обов\'язковим для DICOM-уроку.',
            'dicom_file_upload.required_if' => 'Файл DICOM є обов\'язковим, якщо обрано завантаження файлу.',
            'dicom_url.required_if' => 'URL DICOM є обов\'язковим, якщо обрано зовнішнє посилання.',
            'qa_session_url.required_if' => 'Посилання на Q&A сесію є обов\'язковим для Q&A уроку.',
        ];
    }
}
