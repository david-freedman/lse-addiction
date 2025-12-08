<?php

namespace App\Domains\Lesson\Enums;

enum LessonStatus: string
{
    case Draft = 'draft';
    case Published = 'published';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Чернетка',
            self::Published => 'Опубліковано',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Draft => 'gray',
            self::Published => 'green',
        };
    }
}
