<?php

namespace App\Domains\Course\Enums;
enum CourseStatus: string
{
    case Draft = 'draft';
    case Published = 'published';
    case Archived = 'archived';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Чернетка',
            self::Published => 'Опублікований',
            self::Archived => 'Архівований',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Draft => 'gray',
            self::Published => 'green',
            self::Archived => 'orange',
        };
    }
}
