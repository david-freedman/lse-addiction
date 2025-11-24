<?php

namespace App\Domains\Student\Data;

use Spatie\LaravelData\Attributes\Validation\ArrayType;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;

class BulkDeleteData extends Data
{
    public function __construct(
        #[Required, ArrayType]
        public readonly array $student_ids,
    ) {}

    public static function rules(): array
    {
        return [
            'student_ids' => ['required', 'array', 'min:1'],
            'student_ids.*' => ['required', 'integer', 'exists:students,id'],
        ];
    }
}
