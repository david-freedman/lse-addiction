<?php

namespace App\Domains\Student\ViewModels;

use App\Domains\Course\Models\Course;
use App\Domains\Student\Models\Student;
use App\Domains\Student\Models\StudentGroup;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

readonly class StudentGroupDetailViewModel
{
    private StudentGroup $group;

    private LengthAwarePaginator $students;

    private Collection $availableStudents;

    public function __construct(StudentGroup $group, int $perPage = 20)
    {
        $this->group = $group->load(['course', 'creator']);

        $this->students = $group->students()
            ->orderBy('surname')
            ->orderBy('name')
            ->paginate($perPage);

        $existingStudentIds = $group->students()->pluck('students.id')->toArray();

        $this->availableStudents = Student::query()
            ->whereNotIn('id', $existingStudentIds)
            ->orderBy('surname')
            ->orderBy('name')
            ->limit(100)
            ->get();
    }

    public function group(): StudentGroup
    {
        return $this->group;
    }

    public function students(): LengthAwarePaginator
    {
        return $this->students;
    }

    public function availableStudents(): Collection
    {
        return $this->availableStudents;
    }

    public function course(): ?Course
    {
        return $this->group->course;
    }

    public function creator(): ?User
    {
        return $this->group->creator;
    }

    public function studentsCount(): int
    {
        return $this->group->students()->count();
    }

    public function hasStudents(): bool
    {
        return $this->students->isNotEmpty();
    }

    public function hasAvailableStudents(): bool
    {
        return $this->availableStudents->isNotEmpty();
    }
}
