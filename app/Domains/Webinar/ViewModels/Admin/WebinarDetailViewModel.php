<?php

namespace App\Domains\Webinar\ViewModels\Admin;

use App\Domains\Student\Models\Student;
use App\Domains\Webinar\Models\Webinar;
use Illuminate\Support\Collection;

final readonly class WebinarDetailViewModel
{
    public function __construct(
        private Webinar $webinar,
    ) {}

    public function webinar(): Webinar
    {
        return $this->webinar->load(['teacher', 'students']);
    }

    public function title(): string
    {
        return $this->webinar->title;
    }

    public function description(): ?string
    {
        return $this->webinar->description;
    }

    public function bannerUrl(): ?string
    {
        return $this->webinar->banner_url;
    }

    public function teacherName(): string
    {
        return $this->webinar->teacher?->full_name ?? 'Не вказано';
    }

    public function startsAt(): string
    {
        return $this->webinar->starts_at->format('d.m.Y H:i');
    }

    public function formattedDuration(): string
    {
        return $this->webinar->formatted_duration;
    }

    public function price(): string
    {
        if ($this->webinar->price == 0) {
            return 'Безкоштовно';
        }

        return number_format($this->webinar->price, 0, ',', ' ') . ' ₴';
    }

    public function oldPrice(): ?string
    {
        if (!$this->webinar->old_price || $this->webinar->old_price <= $this->webinar->price) {
            return null;
        }

        return number_format($this->webinar->old_price, 0, ',', ' ') . ' ₴';
    }

    public function statusLabel(): string
    {
        return $this->webinar->status->label();
    }

    public function statusColor(): string
    {
        return $this->webinar->status->color();
    }

    public function meetingUrl(): ?string
    {
        return $this->webinar->meeting_url;
    }

    public function maxParticipants(): ?int
    {
        return $this->webinar->max_participants;
    }

    /**
     * @return Collection<int, Student>
     */
    public function activeRegistrations(): Collection
    {
        return $this->webinar->students
            ->filter(fn (Student $student) => $student->pivot->cancelled_at === null)
            ->values();
    }

    /**
     * @return Collection<int, Student>
     */
    public function cancelledRegistrations(): Collection
    {
        return $this->webinar->students
            ->filter(fn (Student $student) => $student->pivot->cancelled_at !== null)
            ->values();
    }

    public function activeCount(): int
    {
        return $this->activeRegistrations()->count();
    }

    public function cancelledCount(): int
    {
        return $this->cancelledRegistrations()->count();
    }

    public function availableSpots(): ?int
    {
        if ($this->webinar->max_participants === null) {
            return null;
        }

        return max(0, $this->webinar->max_participants - $this->activeCount());
    }

    public function statistics(): array
    {
        return [
            'active_count' => $this->activeCount(),
            'cancelled_count' => $this->cancelledCount(),
            'max_participants' => $this->maxParticipants(),
            'available_spots' => $this->availableSpots(),
        ];
    }
}
