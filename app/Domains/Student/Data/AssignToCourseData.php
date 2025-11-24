<?php

namespace App\Domains\Student\Data;

use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\Numeric;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;

class AssignToCourseData extends Data
{
    public function __construct(
        #[Required, Numeric, Exists('courses', 'id')]
        public readonly int $course_id,

        #[Nullable, Numeric]
        public readonly ?int $teacher_id,

        #[Nullable, Numeric, Min(0), Max(100)]
        public readonly ?float $individual_discount,

        #[Nullable, StringType, Max(1000)]
        public readonly ?string $notes,
    ) {}

    public static function rules(): array
    {
        return [
            'teacher_id' => [
                'nullable',
                'integer',
                'exists:users,id',
                function ($attribute, $value, $fail) {
                    if ($value && !\App\Models\User::where('id', $value)->where('role', 'teacher')->exists()) {
                        $fail('Вибраний користувач не є викладачем.');
                    }
                },
            ],
        ];
    }
}
