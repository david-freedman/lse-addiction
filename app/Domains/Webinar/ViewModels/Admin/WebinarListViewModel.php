<?php

namespace App\Domains\Webinar\ViewModels\Admin;

use App\Domains\Teacher\Models\Teacher;
use App\Domains\Webinar\Data\WebinarFilterData;
use App\Domains\Webinar\Enums\WebinarStatus;
use App\Domains\Webinar\Models\Webinar;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

readonly class WebinarListViewModel
{
    public function __construct(
        private WebinarFilterData $filters,
        private int $perPage = 20,
    ) {}

    public function webinars(): LengthAwarePaginator
    {
        $query = Webinar::query()
            ->with(['teacher'])
            ->withCount(['activeRegistrations']);

        if ($this->filters->search) {
            $query->where('title', 'ilike', "%{$this->filters->search}%");
        }

        if ($this->filters->status) {
            $query->where('status', $this->filters->status);
        }

        if ($this->filters->teacher_id) {
            $query->where('teacher_id', $this->filters->teacher_id);
        }

        if ($this->filters->date_from) {
            $query->whereDate('starts_at', '>=', $this->filters->date_from);
        }

        if ($this->filters->date_to) {
            $query->whereDate('starts_at', '<=', $this->filters->date_to);
        }

        return $query->orderByDesc('starts_at')->paginate($this->perPage);
    }

    public function filters(): WebinarFilterData
    {
        return $this->filters;
    }

    /**
     * @return WebinarStatus[]
     */
    public function statuses(): array
    {
        return WebinarStatus::cases();
    }

    public function teachers(): Collection
    {
        return Teacher::orderBy('last_name')->orderBy('first_name')->get();
    }

    public function hasNoWebinars(): bool
    {
        return $this->webinars()->isEmpty();
    }

    public function isFiltered(): bool
    {
        return $this->filters->search !== null
            || $this->filters->status !== null
            || $this->filters->teacher_id !== null
            || $this->filters->date_from !== null
            || $this->filters->date_to !== null;
    }
}
