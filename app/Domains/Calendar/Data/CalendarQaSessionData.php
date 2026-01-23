<?php

namespace App\Domains\Calendar\Data;

readonly class CalendarQaSessionData
{
    public function __construct(
        public int $id,
        public string $name,
        public string $lessonUrl,
        public ?string $qaSessionUrl,
        public string $date,
        public string $formattedTime,
        public string $courseName,
    ) {}
}
