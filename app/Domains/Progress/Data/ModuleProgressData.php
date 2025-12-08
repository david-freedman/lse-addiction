<?php

namespace App\Domains\Progress\Data;

readonly class ModuleProgressData
{
    public function __construct(
        public int $id,
        public int $courseId,
        public string $name,
        public ?string $description,
        public int $order,
        public int $progressPercentage,
        public int $lessonsCompleted,
        public int $totalLessons,
        public bool $isUnlocked,
        public ?string $unlockMessage,
        public string $iconType,
        public array $recentLessons,
    ) {}
}
