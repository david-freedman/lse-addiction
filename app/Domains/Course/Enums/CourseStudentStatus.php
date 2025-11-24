<?php

namespace App\Domains\Course\Enums;

enum CourseStudentStatus: string
{
    case Active = 'active';
    case Inactive = 'inactive';
    case Completed = 'completed';

    public function label(): string
    {
        return match ($this) {
            self::Active => 'Активний',
            self::Inactive => 'Неактивний',
            self::Completed => 'Завершено',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Active => 'green',
            self::Inactive => 'gray',
            self::Completed => 'blue',
        };
    }
}
