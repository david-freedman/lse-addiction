<?php

namespace App\Domains\Calendar\Data;

readonly class CalendarWebinarData
{
    public function __construct(
        public int $id,
        public string $title,
        public string $slug,
        public string $teacherName,
        public ?string $teacherPhotoUrl,
        public string $date,
        public string $formattedDate,
        public string $formattedTime,
        public string $formattedDuration,
        public int $participantsCount,
        public bool $isRegistered,
    ) {}
}
