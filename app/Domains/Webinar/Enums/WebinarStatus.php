<?php

namespace App\Domains\Webinar\Enums;

enum WebinarStatus: string
{
    case Draft = 'draft';
    case Upcoming = 'upcoming';
    case Live = 'live';
    case Ended = 'ended';
    case Recorded = 'recorded';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Чернетка',
            self::Upcoming => 'Заплановано',
            self::Live => 'Йде зараз',
            self::Ended => 'Завершено',
            self::Recorded => 'У записі',
            self::Cancelled => 'Скасовано',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Draft, self::Ended, self::Cancelled => 'gray',
            self::Upcoming => 'teal',
            self::Live => 'red',
            self::Recorded => 'green',
        };
    }
}
