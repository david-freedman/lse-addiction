<?php

namespace App\Domains\Certificate\Data;

use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;

class PublishCertificatesData extends Data
{
    public function __construct(
        #[Required]
        public readonly array $certificate_ids,
    ) {}

    public static function rules(): array
    {
        return [
            'certificate_ids' => ['required', 'array', 'min:1'],
            'certificate_ids.*' => ['required', 'integer', 'exists:certificates,id'],
        ];
    }
}
