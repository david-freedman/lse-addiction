<?php

namespace App\Domains\Webinar\Enums;

enum WebinarStatus: string
{
    case Draft = 'draft';
    case Upcoming = 'upcoming';
    case Live = 'live';
    case Completed = 'completed';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Чернетка',
            self::Upcoming => 'Заплановано',
            self::Live => 'Йде зараз',
            self::Completed => 'Завершено',
            self::Cancelled => 'Скасовано',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Draft => 'gray',
            self::Upcoming => 'teal',
            self::Live => 'red',
            self::Completed => 'green',
            self::Cancelled => 'gray',
        };
    }
}
