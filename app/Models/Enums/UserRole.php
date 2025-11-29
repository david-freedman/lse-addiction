<?php

namespace App\Models\Enums;

enum UserRole: string
{
    case Admin = 'admin';
    case Teacher = 'teacher';

    public function label(): string
    {
        return match ($this) {
            self::Admin => 'Адміністратор',
            self::Teacher => 'Викладач',
        };
    }
}
