<?php

namespace App\Domains\Module\Data;

readonly class LessonCardData
{
    public function __construct(
        public int $id,
        public string $name,
        public ?string $description,
        public int $order,
        public string $type,
        public string $iconType,
        public ?string $duration,
        public bool $isCompleted,
        public string $url,
        public array $colorScheme,
        public bool $hasHomework = false,
        public ?string $formattedDate = null,
        public ?string $formattedTime = null,
        public bool $isFinal = false,
    ) {}
}
