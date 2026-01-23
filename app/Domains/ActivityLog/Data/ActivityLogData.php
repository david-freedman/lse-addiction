<?php

namespace App\Domains\ActivityLog\Data;

use App\Domains\ActivityLog\Enums\ActivitySubject;
use App\Domains\ActivityLog\Enums\ActivityType;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;

class ActivityLogData extends Data
{
    public function __construct(
        #[Required]
        public readonly ActivitySubject $subject_type,
        #[Nullable]
        public readonly ?int $subject_id,
        #[Required]
        public readonly ActivityType $activity_type,
        #[Required, StringType]
        public readonly string $description,
        #[Nullable]
        public readonly ?array $properties,
        #[Nullable]
        public readonly ?int $performed_by = null,
        #[Nullable]
        public readonly ?int $course_id = null,
        #[Nullable, StringType]
        public readonly ?string $ip_address = null,
        #[Nullable, StringType]
        public readonly ?string $user_agent = null,
    ) {}
}
