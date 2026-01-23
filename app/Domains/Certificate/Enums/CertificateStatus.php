<?php

namespace App\Domains\Certificate\Enums;

enum CertificateStatus: string
{
    case Pending = 'pending';
    case Published = 'published';
    case Revoked = 'revoked';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Очікує модерації',
            self::Published => 'Опубліковано',
            self::Revoked => 'Скасовано',
        };
    }

    public function badgeClasses(): string
    {
        return match ($this) {
            self::Pending => 'bg-warning-100 text-warning-700',
            self::Published => 'bg-success-100 text-success-700',
            self::Revoked => 'bg-error-100 text-error-700',
        };
    }
}
