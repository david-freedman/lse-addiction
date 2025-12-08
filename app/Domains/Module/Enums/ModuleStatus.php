<?php

namespace App\Domains\Module\Enums;

enum ModuleStatus: string
{
    case Active = 'active';
    case Hidden = 'hidden';

    public function label(): string
    {
        return match ($this) {
            self::Active => 'Активний',
            self::Hidden => 'Прихований',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Active => 'green',
            self::Hidden => 'gray',
        };
    }
}
