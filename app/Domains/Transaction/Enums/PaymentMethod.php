<?php

namespace App\Domains\Transaction\Enums;

enum PaymentMethod: string
{
    case Visa = 'visa';
    case Mastercard = 'mastercard';
    case ApplePay = 'apple_pay';
    case GooglePay = 'google_pay';

    public function label(): string
    {
        return match ($this) {
            self::Visa => 'Visa',
            self::Mastercard => 'Mastercard',
            self::ApplePay => 'Apple Pay',
            self::GooglePay => 'Google Pay',
        };
    }
}
