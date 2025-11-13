<?php

namespace App\Domains\Payment\Enums;

enum PaymentProvider: string
{
    case WayForPay = 'wayforpay';
    case Stripe = 'stripe';
    case LiqPay = 'liqpay';

    public function label(): string
    {
        return match ($this) {
            self::WayForPay => 'WayForPay',
            self::Stripe => 'Stripe',
            self::LiqPay => 'LiqPay',
        };
    }
}
