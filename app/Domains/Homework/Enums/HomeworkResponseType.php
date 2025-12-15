<?php

namespace App\Domains\Homework\Enums;

enum HomeworkResponseType: string
{
    case Text = 'text';
    case Files = 'files';
    case Both = 'both';

    public function label(): string
    {
        return match ($this) {
            self::Text => 'Текстова відповідь',
            self::Files => 'Завантаження файлів',
            self::Both => 'Текст та файли',
        };
    }

    public function allowsText(): bool
    {
        return $this === self::Text || $this === self::Both;
    }

    public function allowsFiles(): bool
    {
        return $this === self::Files || $this === self::Both;
    }
}
