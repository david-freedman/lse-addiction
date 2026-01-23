<?php

namespace App\Domains\Webinar\ViewModels\Student;

use App\Domains\Student\Models\Student;
use App\Domains\Webinar\Enums\WebinarStatus;
use App\Domains\Webinar\Models\Webinar;
use Illuminate\Database\Eloquent\Collection;

readonly class MyWebinarsViewModel
{
    private Collection $webinars;

    private Collection $allWebinars;

    private ?string $currentStatus;

    public function __construct(
        private Student $student,
        ?string $status = null
    ) {
        $this->currentStatus = $status;

        $baseQuery = $student->webinars()
            ->wherePivotNull('cancelled_at')
            ->where('status', '!=', WebinarStatus::Cancelled)
            ->where('status', '!=', WebinarStatus::Draft)
            ->with('teacher')
            ->orderBy('starts_at', 'desc');

        $this->allWebinars = (clone $baseQuery)->get();

        if ($status === 'upcoming') {
            $this->webinars = (clone $baseQuery)
                ->where('starts_at', '>', now())
                ->orderBy('starts_at')
                ->get();
        } elseif ($status === 'past') {
            $this->webinars = (clone $baseQuery)
                ->where('starts_at', '<=', now())
                ->get();
        } else {
            $this->webinars = $this->allWebinars;
        }
    }

    public function webinars(): Collection
    {
        return $this->webinars;
    }

    public function hasWebinars(): bool
    {
        return $this->webinars->isNotEmpty();
    }

    public function hasNoWebinars(): bool
    {
        return $this->webinars->isEmpty();
    }

    public function webinarsCount(): int
    {
        return $this->webinars->count();
    }

    public function totalCount(): int
    {
        return $this->allWebinars->count();
    }

    public function upcomingCount(): int
    {
        return $this->allWebinars->where('starts_at', '>', now())->count();
    }

    public function pastCount(): int
    {
        return $this->allWebinars->where('starts_at', '<=', now())->count();
    }

    public function liveCount(): int
    {
        return $this->allWebinars->where('status', WebinarStatus::Live)->count();
    }

    public function currentStatus(): ?string
    {
        return $this->currentStatus;
    }

    public function isFilteredBy(string $status): bool
    {
        return $this->currentStatus === $status;
    }

    public function isShowingAll(): bool
    {
        return $this->currentStatus === null;
    }

    public function isRegistered(Webinar $webinar): bool
    {
        return true;
    }

    public function getWebinarCardData(Webinar $webinar): object
    {
        return (object) [
            'slug' => $webinar->slug,
            'title' => $webinar->title,
            'teacherName' => $webinar->teacher?->full_name ?? 'Не вказано',
            'teacherPhotoUrl' => $webinar->teacher?->photo_url,
            'formattedDate' => $webinar->formattedDate,
            'formattedTime' => $webinar->formattedTime,
            'formattedDuration' => $webinar->formattedDuration,
            'formattedDatetime' => $webinar->formattedDate . ' о ' . $webinar->formattedTime,
            'participantsCount' => $webinar->participantsCount(),
            'isStartingSoon' => $webinar->isStartingSoon,
            'isLive' => $webinar->status === WebinarStatus::Live,
            'isEnded' => $webinar->status === WebinarStatus::Ended,
            'isRecorded' => $webinar->status === WebinarStatus::Recorded,
            'isUpcoming' => $webinar->status === WebinarStatus::Upcoming,
            'isRegistered' => true,
            'isPast' => $webinar->starts_at <= now(),
            'bannerUrl' => $webinar->bannerUrl,
            'price' => $webinar->price,
            'isFree' => $webinar->isFree,
            'availableSpots' => $webinar->availableSpots,
        ];
    }
}
