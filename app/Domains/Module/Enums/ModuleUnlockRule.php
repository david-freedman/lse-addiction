<?php

namespace App\Domains\Module\Enums;

enum ModuleUnlockRule: string
{
    case None = 'none';
    case CompletePrevious = 'complete_previous';
    case CompleteTest = 'complete_test';

    public function label(): string
    {
        return match ($this) {
            self::None => 'Доступний одразу',
            self::CompletePrevious => 'Завершити попередній модуль',
            self::CompleteTest => 'Пройти тест попереднього модуля',
        };
    }
}
