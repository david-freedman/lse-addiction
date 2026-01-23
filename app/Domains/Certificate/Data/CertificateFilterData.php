<?php

namespace App\Domains\Certificate\Data;

use App\Domains\Certificate\Enums\CertificateStatus;
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
        public readonly ?string $status = null,
    ) {}

    public function getStatusEnum(): ?CertificateStatus
    {
        if ($this->status === null) {
            return null;
        }

        return CertificateStatus::tryFrom($this->status);
    }
}
