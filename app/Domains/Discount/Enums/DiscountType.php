<?php

namespace App\Domains\Discount\Enums;

enum DiscountType: string
{
    case Percentage = 'percentage';
    case Fixed = 'fixed';

    public function label(): string
    {
        return match ($this) {
            self::Percentage => 'Відсотки',
            self::Fixed => 'Фіксована сума',
        };
    }

    public function suffix(): string
    {
        return match ($this) {
            self::Percentage => '%',
            self::Fixed => 'грн',
        };
    }
}
