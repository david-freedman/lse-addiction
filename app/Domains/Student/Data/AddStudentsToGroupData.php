<?php

namespace App\Domains\Student\Data;

use Spatie\LaravelData\Attributes\Validation\ArrayType;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;

class AddStudentsToGroupData extends Data
{
    public function __construct(
        #[Required, ArrayType]
        public readonly array $student_ids,
    ) {}

    public static function rules(): array
    {
        return [
            'student_ids.*' => ['integer', 'exists:students,id'],
        ];
    }
}
