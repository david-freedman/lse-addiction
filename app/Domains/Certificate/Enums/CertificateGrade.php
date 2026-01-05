<?php

namespace App\Domains\Certificate\Enums;

enum CertificateGrade: string
{
    case Excellent = 'excellent';
    case Good = 'good';
    case Satisfactory = 'satisfactory';
    case Pass = 'pass';

    public static function fromScore(float $score): self
    {
        return match (true) {
            $score >= 90 => self::Excellent,
            $score >= 75 => self::Good,
            $score >= 60 => self::Satisfactory,
            default => self::Pass,
        };
    }

    public function label(): string
    {
        return match ($this) {
            self::Excellent => 'Відмінно',
            self::Good => 'Добре',
            self::Satisfactory => 'Задовільно',
            self::Pass => 'Зараховано',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Excellent => 'green',
            self::Good => 'blue',
            self::Satisfactory => 'yellow',
            self::Pass => 'gray',
        };
    }

    public function badgeClasses(): string
    {
        return match ($this) {
            self::Excellent => 'bg-green-100 text-green-700',
            self::Good => 'bg-blue-100 text-blue-700',
            self::Satisfactory => 'bg-yellow-100 text-yellow-700',
            self::Pass => 'bg-gray-100 text-gray-700',
        };
    }
}
