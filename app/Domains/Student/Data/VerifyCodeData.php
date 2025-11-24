<?php

namespace App\Domains\Student\Data;

use Spatie\LaravelData\Data;

class VerifyCodeData extends Data
{
    public function __construct(
        public readonly string $contact,
        public readonly string $code,
        public readonly string $type,
    ) {}

    public static function rules(): array
    {
        return [
            'contact' => ['required', 'string'],
            'code' => ['required', 'string', 'size:4'],
            'type' => ['required', 'in:email,phone'],
        ];
    }
}
