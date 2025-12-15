<?php

namespace App\Domains\Certificate\ViewModels;

use App\Domains\Certificate\Models\Certificate;
use App\Domains\Student\Models\Student;
use Illuminate\Database\Eloquent\Collection;

readonly class CertificatesListViewModel
{
    private Collection $certificates;

    private ?string $search;

    public function __construct(
        private Student $student,
        ?string $search = null
    ) {
        $this->search = $search;

        $query = Certificate::forStudent($student->id)
            ->with(['course.teacher'])
            ->orderBy('issued_at', 'desc');

        if ($search) {
            $query->search($search);
        }

        $this->certificates = $query->get();
    }

    public function certificates(): Collection
    {
        return $this->certificates;
    }

    public function hasCertificates(): bool
    {
        return $this->certificates->isNotEmpty();
    }

    public function hasNoCertificates(): bool
    {
        return $this->certificates->isEmpty();
    }

    public function totalCount(): int
    {
        return $this->certificates->count();
    }

    public function totalStudyHours(): int
    {
        return $this->certificates->sum('study_hours');
    }

    public function formattedTotalStudyHours(): string
    {
        $hours = intdiv($this->totalStudyHours(), 60);

        return $hours.' год';
    }

    public function lastCertificate(): ?Certificate
    {
        return $this->certificates->first();
    }

    public function currentSearch(): ?string
    {
        return $this->search;
    }

    public function isFiltered(): bool
    {
        return $this->search !== null && $this->search !== '';
    }
}
