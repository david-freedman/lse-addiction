<?php

namespace App\Domains\Customer\Data;

use Spatie\LaravelData\Attributes\Validation\Before;
use Spatie\LaravelData\Attributes\Validation\Date;
use Spatie\LaravelData\Attributes\Validation\DateFormat;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;

class UpdatePersonalDetailsData extends Data
{
    public function __construct(
        #[Required, StringType, Min(2), Max(255)]
        public readonly string $surname,
        #[Required, StringType, Min(2), Max(255)]
        public readonly string $name,
        #[Required, Date, DateFormat('Y-m-d'), Before('today')]
        public readonly string $birthday,
        #[Required, StringType, Min(2), Max(255)]
        public readonly string $city,
    ) {}

    public static function rules(): array
    {
        return [
            'birthday' => ['required', 'date', 'date_format:Y-m-d', 'before:today', 'before_or_equal:' . now()->subYears(18)->format('Y-m-d')],
        ];
    }
}
