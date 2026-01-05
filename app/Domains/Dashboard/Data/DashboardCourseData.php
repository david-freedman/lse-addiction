<?php

namespace App\Domains\Dashboard\Data;

readonly class DashboardCourseData
{
    public function __construct(
        public int $id,
        public string $name,
        public string $slug,
        public int $lessonsCompleted,
        public int $totalLessons,
        public int $progressPercentage,
        public string $continueUrl,
    ) {}
}
