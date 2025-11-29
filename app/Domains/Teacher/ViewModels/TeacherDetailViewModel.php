<?php

namespace App\Domains\Teacher\ViewModels;

use App\Domains\Teacher\Models\Teacher;

readonly class TeacherDetailViewModel
{
    public function __construct(private Teacher $teacher) {}

    public function teacher(): Teacher
    {
        return $this->teacher->load('user');
    }

    public function canDelete(): bool
    {
        return true;
    }

    public function deleteBlockedReason(): ?string
    {
        return null;
    }
}
