<?php

namespace App\Domains\User\Data;

use Illuminate\Http\UploadedFile;
use Spatie\LaravelData\Attributes\Validation\BooleanType;
use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Attributes\Validation\Image;
use Spatie\LaravelData\Attributes\Validation\In;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Mimes;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Attributes\Validation\Unique;
use Spatie\LaravelData\Data;

class CreateUserData extends Data
{
    public function __construct(
        #[Required, StringType, Min(2), Max(255)]
        public readonly string $name,

        #[Required, Email, Unique('users', 'email')]
        public readonly string $email,

        #[Required, StringType, In(['admin', 'teacher'])]
        public readonly string $role,

        #[Nullable, Image, Mimes(['jpeg', 'jpg', 'png', 'webp']), Max(5120)]
        public readonly ?UploadedFile $photo,

        #[Nullable, BooleanType]
        public readonly ?bool $is_active = true,
    ) {}
}
