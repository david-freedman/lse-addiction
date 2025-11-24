<?php

namespace App\Domains\Student\Data;

use App\Domains\Shared\Casts\EmailDataCast;
use App\Domains\Shared\Casts\PhoneDataCast;
use App\Domains\Shared\Rules\ValidEmail;
use App\Domains\Shared\Rules\ValidPhone;
use App\Domains\Shared\ValueObjects\Email;
use App\Domains\Shared\ValueObjects\Phone;
use Illuminate\Http\UploadedFile;
use Spatie\LaravelData\Attributes\Validation\Before;
use Spatie\LaravelData\Attributes\Validation\BooleanType;
use Spatie\LaravelData\Attributes\Validation\Image;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Mimes;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\Rule;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Attributes\Validation\Unique;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Data;

class CreateStudentData extends Data
{
    public function __construct(
        #[Required, StringType]
        #[Rule(new ValidEmail)]
        #[Unique('students', 'email')]
        #[WithCast(EmailDataCast::class)]
        public readonly Email $email,

        #[Required, StringType]
        #[Rule(new ValidPhone)]
        #[Unique('students', 'phone')]
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
            'birthday' => ['nullable', 'date', 'before:today'],
        ];
    }
}
