<?php

namespace App\Domains\Teacher\Data;

use Illuminate\Http\UploadedFile;
use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Attributes\Validation\Image;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Mimes;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Attributes\Validation\Unique;
use Spatie\LaravelData\Data;

class CreateTeacherData extends Data
{
    public function __construct(
        #[Required, StringType, Max(100)]
        public readonly string $first_name,

        #[Required, StringType, Max(100)]
        public readonly string $last_name,

        #[Nullable, StringType, Max(100)]
        public readonly ?string $middle_name,

        #[Required, Email, Unique('users', 'email')]
        public readonly string $email,

        #[Required, StringType, Max(255)]
        public readonly string $specialization,

        #[Nullable, StringType, Max(255)]
        public readonly ?string $position,

        #[Nullable, StringType, Max(255)]
        public readonly ?string $workplace,

        #[Nullable, StringType, Max(5000)]
        public readonly ?string $description,

        #[Required, Image, Mimes(['jpeg', 'jpg', 'png', 'webp']), Max(5120)]
        public readonly UploadedFile $photo,
    ) {}
}
