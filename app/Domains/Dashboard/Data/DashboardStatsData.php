<?php

namespace App\Domains\Dashboard\Data;

readonly class DashboardStatsData
{
    public function __construct(
        public int $coursesInProgress,
        public int $coursesInProgressDelta,
        public int $completedCourses,
        public int $completedCoursesDelta,
        public int $studyHours,
        public int $studyHoursDelta,
        public int $certificates,
        public int $certificatesDelta,
    ) {}
}
