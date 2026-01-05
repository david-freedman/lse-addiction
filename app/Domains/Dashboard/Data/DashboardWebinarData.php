<?php

namespace App\Domains\Dashboard\Data;

use App\Domains\Webinar\Enums\WebinarStatus;

readonly class DashboardWebinarData
{
    public function __construct(
        public int $id,
        public string $title,
        public string $slug,
        public string $teacherName,
        public ?string $teacherPhotoUrl,
        public string $formattedDate,
        public string $formattedTime,
        public string $formattedDuration,
        public string $formattedDatetime,
        public int $participantsCount,
        public bool $isStartingSoon,
        public bool $isRegistered,
        public bool $isLive,
        public bool $isUpcoming,
        public bool $isCompleted,
        public WebinarStatus $status,
        public string $price,
        public bool $isFree,
        public ?string $bannerUrl,
        public ?int $availableSpots,
    ) {}
}
