<?php

namespace App\Domains\Certificate\ViewModels\Admin;

use App\Domains\Certificate\Data\CertificateFilterData;
use App\Domains\Certificate\Models\Certificate;
use App\Domains\Course\Models\Course;
use App\Domains\Student\Models\Student;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

readonly class CertificateListViewModel
{
    public function __construct(
        private CertificateFilterData $filters,
        private int $perPage = 20,
    ) {}

    public function certificates(): LengthAwarePaginator
    {
        $query = Certificate::query()
            ->withTrashed()
            ->with(['student', 'course', 'issuedBy']);

        if ($this->filters->search) {
            $search = $this->filters->search;
            $query->where(function ($q) use ($search) {
                $q->where('certificate_number', 'ilike', "%{$search}%")
                    ->orWhereHas('student', function ($q) use ($search) {
                        $q->where('name', 'ilike', "%{$search}%")
                            ->orWhere('surname', 'ilike', "%{$search}%")
                            ->orWhere('email', 'ilike', "%{$search}%");
                    })
                    ->orWhereHas('course', function ($q) use ($search) {
                        $q->where('name', 'ilike', "%{$search}%");
                    });
            });
        }

        if ($this->filters->course_id) {
            $query->where('course_id', $this->filters->course_id);
        }

        if ($this->filters->student_id) {
            $query->where('student_id', $this->filters->student_id);
        }

        if ($this->filters->only_revoked === true) {
            $query->onlyTrashed();
        } elseif ($this->filters->only_revoked === false) {
            $query->withoutTrashed();
        }

        return $query->orderByDesc('issued_at')->paginate($this->perPage)->withQueryString();
    }

    public function filters(): CertificateFilterData
    {
        return $this->filters;
    }

    public function courses(): Collection
    {
        return Course::orderBy('name')->get(['id', 'name']);
    }

    public function students(): Collection
    {
        return Student::orderBy('surname')
            ->orderBy('name')
            ->get(['id', 'name', 'surname', 'email']);
    }

    public function hasNoCertificates(): bool
    {
        return $this->certificates()->isEmpty();
    }

    public function isFiltered(): bool
    {
        return $this->filters->search !== null
            || $this->filters->course_id !== null
            || $this->filters->student_id !== null
            || $this->filters->only_revoked !== null;
    }
}
