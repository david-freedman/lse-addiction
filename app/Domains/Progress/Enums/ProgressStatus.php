<?php

namespace App\Domains\Progress\Enums;

enum ProgressStatus: string
{
    case NotStarted = 'not_started';
    case InProgress = 'in_progress';
    case Completed = 'completed';

    public function label(): string
    {
        return match ($this) {
            self::NotStarted => 'Не розпочато',
            self::InProgress => 'В процесі',
            self::Completed => 'Завершено',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::NotStarted => 'gray',
            self::InProgress => 'blue',
            self::Completed => 'green',
        };
    }
}
