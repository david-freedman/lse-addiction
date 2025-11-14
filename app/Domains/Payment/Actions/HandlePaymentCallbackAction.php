<?php

namespace App\Domains\Payment\Actions;

use App\Domains\ActivityLog\Actions\LogActivityAction;
use App\Domains\ActivityLog\Data\ActivityLogData;
use App\Domains\ActivityLog\Enums\ActivitySubject;
use App\Domains\ActivityLog\Enums\ActivityType;
use App\Domains\Course\Actions\CompleteCoursePurchaseAction;
use App\Domains\Course\Models\Course;
use App\Domains\Payment\Contracts\PaymentGatewayInterface;
use App\Domains\Payment\Enums\PaymentProvider;
use App\Domains\Payment\Gateways\WayForPayGateway;
use App\Domains\Transaction\Actions\CompleteTransactionAction;
use App\Domains\Transaction\Actions\FailTransactionAction;
use App\Domains\Transaction\Enums\PaymentMethod;
use App\Domains\Transaction\Models\Transaction;
use Illuminate\Support\Facades\Log;

class HandlePaymentCallbackAction
{
    public static function execute(array $callbackData, PaymentProvider $provider): array
    {
        $gateway = self::resolveGateway($provider);

        if (! $gateway->verifyCallback($callbackData)) {
            Log::warning('Payment callback signature verification failed', [
                'callback_data' => $callbackData,
            ]);

            throw new \RuntimeException('Invalid callback signature');
        }

        $callback = $gateway->parseCallback($callbackData);

        $transaction = Transaction::where('transaction_number', $callback->orderReference)->firstOrFail();

        $transaction->update([
            'metadata' => array_merge($transaction->metadata ?? [], [
                'gateway_transaction_id' => $callback->authCode,
                'gateway_response' => $callbackData,
                'payment_provider' => $provider->value,
            ]),
            'payment_reference' => $callback->cardPan,
            'payment_method' => $callback->cardType ? self::mapCardTypeToPaymentMethod($callback->cardType) : null,
        ]);

        if ($callback->transactionStatus === 'Approved') {
            CompleteTransactionAction::execute($transaction);

            if ($transaction->purchasable_type === Course::class) {
                CompleteCoursePurchaseAction::execute($transaction);
            }

            LogActivityAction::execute(ActivityLogData::from([
                'subject_type' => ActivitySubject::Transaction,
                'subject_id' => $transaction->id,
                'activity_type' => ActivityType::TransactionCompleted,
                'description' => 'Payment approved by gateway',
                'properties' => [
                    'transaction_number' => $transaction->transaction_number,
                    'provider' => $provider->value,
                    'auth_code' => $callback->authCode,
                    'card_type' => $callback->cardType,
                ],
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]));
        } else {
            FailTransactionAction::execute($transaction);

            LogActivityAction::execute(ActivityLogData::from([
                'subject_type' => ActivitySubject::Transaction,
                'subject_id' => $transaction->id,
                'activity_type' => ActivityType::TransactionCompleted,
                'description' => 'Payment declined by gateway',
                'properties' => [
                    'transaction_number' => $transaction->transaction_number,
                    'provider' => $provider->value,
                    'reason_code' => $callback->reasonCode,
                ],
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]));
        }

        return $gateway->generateCallbackResponse($callback);
    }

    private static function resolveGateway(PaymentProvider $provider): PaymentGatewayInterface
    {
        return match ($provider) {
            PaymentProvider::WayForPay => new WayForPayGateway,
            default => throw new \RuntimeException("Gateway {$provider->value} not implemented"),
        };
    }

    private static function mapCardTypeToPaymentMethod(?string $cardType): ?PaymentMethod
    {
        if (! $cardType) {
            return null;
        }

        return match (strtolower($cardType)) {
            'visa' => PaymentMethod::Visa,
            'mastercard', 'mc' => PaymentMethod::Mastercard,
            'applepay', 'apple_pay' => PaymentMethod::ApplePay,
            'googlepay', 'google_pay' => PaymentMethod::GooglePay,
            default => PaymentMethod::Visa,
        };
    }
}
