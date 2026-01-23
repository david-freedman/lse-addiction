<?php

namespace App\Domains\Student\Enums;

enum ConsentType: string
{
    case PrivacyPolicy = 'privacy_policy';
    case PublicOffer = 'public_offer';

    public function label(): string
    {
        return match ($this) {
            self::PrivacyPolicy => 'Політика обробки персональних даних',
            self::PublicOffer => 'Умови публічної оферти',
        };
    }
}
