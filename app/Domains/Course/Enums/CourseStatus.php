<?php

namespace App\Domains\Course\Enums;

enum CourseStatus: string
{
    case Draft = 'draft';
    case Active = 'active';
    case Archived = 'archived';
    case Hidden = 'hidden';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Чернетка',
            self::Active => 'Активний',
            self::Archived => 'Архівований',
            self::Hidden => 'Прихований',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Draft => 'gray',
            self::Active => 'green',
            self::Archived => 'orange',
            self::Hidden => 'purple',
        };
    }
}
