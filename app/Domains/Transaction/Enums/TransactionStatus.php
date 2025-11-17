<?php

namespace App\Domains\Transaction\Enums;

enum TransactionStatus: string
{
    case Pending = 'pending';
    case Processing = 'processing';
    case Completed = 'completed';
    case Failed = 'failed';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Очікується',
            self::Processing => 'В обробці',
            self::Completed => 'Завершено',
            self::Failed => 'Скасовано',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Pending => 'gray',
            self::Processing => 'orange',
            self::Completed => 'green',
            self::Failed => 'red',
        };
    }
}
