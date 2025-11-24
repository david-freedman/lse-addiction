<?php

namespace App\Domains\Student\Data;

use App\Domains\Shared\Casts\EmailDataCast;
use App\Domains\Shared\Casts\PhoneDataCast;
use App\Domains\Shared\ValueObjects\Email;
use App\Domains\Shared\ValueObjects\Phone;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Rule;
use Spatie\LaravelData\Attributes\Validation\Before;
use Spatie\LaravelData\Attributes\Validation\BooleanType;
use Spatie\LaravelData\Attributes\Validation\Image;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Mimes;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Data;

class UpdateStudentData extends Data
{
    public function __construct(
        #[Required]
        public readonly int $studentId,

        #[Required, StringType]
        #[WithCast(EmailDataCast::class)]
        public readonly Email $email,

        #[Required, StringType]
        #[WithCast(PhoneDataCast::class)]
        public readonly Phone $phone,

        #[Required, StringType, Min(2), Max(100)]
        public readonly string $name,

        #[Required, StringType, Min(2), Max(100)]
        public readonly string $surname,

        #[Nullable, Before('today')]
        public readonly ?string $birthday,

        #[Nullable, StringType, Max(100)]
        public readonly ?string $city,

        #[Nullable, Image, Mimes(['jpeg', 'jpg', 'png', 'webp']), Max(5120)]
        public readonly ?UploadedFile $profile_photo,

        #[Nullable, BooleanType]
        public readonly ?bool $email_verified,

        #[Nullable, BooleanType]
        public readonly ?bool $phone_verified,
    ) {}

    public static function rules(): array
    {
        return [
            'email' => [
                'required',
                'string',
                new \App\Domains\Shared\Rules\ValidEmail,
                Rule::unique('students', 'email')->ignore(request()->route('student')),
            ],
            'phone' => [
                'required',
                'string',
                new \App\Domains\Shared\Rules\ValidPhone,
                Rule::unique('students', 'phone')->ignore(request()->route('student')),
            ],
            'birthday' => ['nullable', 'date', 'before:today'],
        ];
    }
}
