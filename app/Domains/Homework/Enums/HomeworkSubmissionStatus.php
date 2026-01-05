<?php

namespace App\Domains\Homework\Enums;

enum HomeworkSubmissionStatus: string
{
    case Pending = 'pending';
    case RevisionRequested = 'revision_requested';
    case Approved = 'approved';
    case Rejected = 'rejected';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'На перевірці',
            self::RevisionRequested => 'Потребує доопрацювання',
            self::Approved => 'Зараховано',
            self::Rejected => 'Не зараховано',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Pending => 'warning',
            self::RevisionRequested => 'info',
            self::Approved => 'success',
            self::Rejected => 'error',
        };
    }

    public function isPassed(): bool
    {
        return $this === self::Approved;
    }

    public function canResubmit(): bool
    {
        return $this === self::RevisionRequested || $this === self::Rejected;
    }
}
