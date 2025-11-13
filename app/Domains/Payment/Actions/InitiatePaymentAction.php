<?php

namespace App\Domains\Payment\Actions;

use App\Domains\Payment\Contracts\PaymentGatewayInterface;
use App\Domains\Payment\Data\PaymentRequestData;
use App\Domains\Payment\Enums\PaymentProvider;
use App\Domains\Payment\Gateways\WayForPayGateway;
use App\Domains\Transaction\Enums\TransactionStatus;
use App\Domains\Transaction\Models\Transaction;

class InitiatePaymentAction
{
    public static function execute(Transaction $transaction, PaymentProvider $provider): PaymentRequestData
    {
        if (! $transaction->isPending()) {
            throw new \RuntimeException('Transaction must be in pending status');
        }

        $gateway = self::resolveGateway($provider);

        $transaction->update([
            'status' => TransactionStatus::Processing,
            'metadata' => array_merge($transaction->metadata ?? [], [
                'payment_provider' => $provider->value,
            ]),
        ]);

        return $gateway->preparePayment($transaction);
    }

    private static function resolveGateway(PaymentProvider $provider): PaymentGatewayInterface
    {
        return match ($provider) {
            PaymentProvider::WayForPay => new WayForPayGateway,
            default => throw new \RuntimeException("Gateway {$provider->value} not implemented"),
        };
    }
}
