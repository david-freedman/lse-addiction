<?php

namespace App\Domains\Calendar\Data;

readonly class CalendarCourseData
{
    public function __construct(
        public int $id,
        public string $name,
        public string $slug,
        public string $teacherName,
        public ?string $teacherPhotoUrl,
        public string $date,
        public string $formattedDate,
        public ?string $label,
    ) {}
}
