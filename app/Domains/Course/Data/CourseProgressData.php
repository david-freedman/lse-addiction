<?php

namespace App\Domains\Course\Data;

readonly class CourseProgressData
{
    public function __construct(
        public int $courseId,
        public int $progressPercentage,
        public int $lessonsCompleted,
        public int $totalLessons,
    ) {}
}
