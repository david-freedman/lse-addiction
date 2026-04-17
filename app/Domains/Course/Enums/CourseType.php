<?php

namespace App\Domains\Course\Enums;

enum CourseType: string
{
    case Upcoming = 'upcoming';
    case Recorded = 'recorded';
    case Free = 'free';

    public function label(): string
    {
        return match ($this) {
            self::Upcoming => 'Незабаром',
            self::Recorded => 'Курс у записі',
            self::Free     => 'Безкоштовно',
        };
    }
}
