<?php

namespace App\Domains\Lesson\Enums;

enum DicomSourceType: string
{
    case File = 'file';
    case Url = 'url';

    public function label(): string
    {
        return match ($this) {
            self::File => 'Завантажений файл',
            self::Url => 'Зовнішнє посилання',
        };
    }
}
