<?php

namespace App\Domains\Certificate\Data;

use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Data;

class CertificateFilterData extends Data
{
    public function __construct(
        #[Nullable]
        public readonly ?string $search = null,

        #[Nullable]
        public readonly ?int $course_id = null,

        #[Nullable]
        public readonly ?int $student_id = null,

        #[Nullable]
        public readonly ?bool $only_revoked = null,
    ) {}
}
