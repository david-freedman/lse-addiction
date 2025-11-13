<?php

namespace App\Domains\Payment\Actions;

use App\Domains\Payment\Contracts\PaymentGatewayInterface;
use App\Domains\Payment\Enums\PaymentProvider;
use App\Domains\Payment\Gateways\WayForPayGateway;

class VerifyPaymentSignatureAction
{
    public static function execute(array $callbackData, PaymentProvider $provider): bool
    {
        $gateway = self::resolveGateway($provider);

        return $gateway->verifyCallback($callbackData);
    }

    private static function resolveGateway(PaymentProvider $provider): PaymentGatewayInterface
    {
        return match ($provider) {
            PaymentProvider::WayForPay => new WayForPayGateway,
            default => throw new \RuntimeException("Gateway {$provider->value} not implemented"),
        };
    }
}
