<?php

namespace App\Domains\Progress\Data;

readonly class LessonProgressData
{
    public function __construct(
        public int $id,
        public string $name,
        public string $type,
        public bool $isCompleted,
        public ?string $duration,
        public int $order,
    ) {}
}
