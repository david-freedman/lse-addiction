<?php

namespace App\Domains\Payment\Gateways;

use App\Domains\Payment\Contracts\PaymentGatewayInterface;
use App\Domains\Payment\Data\PaymentCallbackData;
use App\Domains\Payment\Data\PaymentProductData;
use App\Domains\Payment\Data\PaymentRequestData;
use App\Domains\Payment\ValueObjects\PaymentSignature;
use App\Domains\Transaction\Models\Transaction;

class WayForPayGateway implements PaymentGatewayInterface
{
    private string $merchantAccount;

    private string $secretKey;

    private string $merchantDomain;

    public function __construct()
    {
        $this->merchantAccount = config('payment.wayforpay.merchant_account');
        $this->secretKey = config('payment.wayforpay.secret_key');
        $this->merchantDomain = config('payment.wayforpay.merchant_domain');
    }

    public function preparePayment(Transaction $transaction): PaymentRequestData
    {
        $customer = $transaction->customer;
        $products = $this->getProductsFromTransaction($transaction);
        $orderDate = now()->timestamp;

        $signatureFields = [
            $this->merchantAccount,
            $this->merchantDomain,
            $transaction->transaction_number,
            $orderDate,
            (float) $transaction->amount,
            $transaction->currency,
            ...$products->map(fn ($p) => $p->name)->toArray(),
            ...$products->map(fn ($p) => $p->count)->toArray(),
            ...$products->map(fn ($p) => (float) $p->price)->toArray(),
        ];

        $signature = PaymentSignature::generate($signatureFields, $this->secretKey);

        return PaymentRequestData::from([
            'merchantAccount' => $this->merchantAccount,
            'merchantDomainName' => $this->merchantDomain,
            'merchantSignature' => $signature->value,
            'orderReference' => $transaction->transaction_number,
            'orderDate' => $orderDate,
            'amount' => $transaction->amount,
            'currency' => $transaction->currency,
            'products' => $products,
            'merchantTransactionSecureType' => 'AUTO',
            'returnUrl' => route('customer.payment.return'),
            'serviceUrl' => route('customer.payment.callback'),
            'clientFirstName' => $customer->name ?? 'Клієнт',
            'clientLastName' => $customer->surname ?? 'LSE',
            'clientPhone' => $customer->phone ?? '',
            'clientEmail' => $customer->email,
            'language' => config('payment.wayforpay.language', 'UA'),
            'apiVersion' => config('payment.wayforpay.api_version', 2),
            'clientAccountId' => (string) $customer->id,
            'orderTimeout' => config('payment.wayforpay.order_timeout'),
            'paymentSystems' => config('payment.wayforpay.payment_systems'),
            'defaultPaymentSystem' => config('payment.wayforpay.default_payment_system'),
        ]);
    }

    public function verifyCallback(array $data): bool
    {
        $signatureFields = [
            $data['merchantAccount'] ?? '',
            $data['orderReference'] ?? '',
            (string) ($data['amount'] ?? ''),
            $data['currency'] ?? '',
            $data['authCode'] ?? '',
            $data['cardPan'] ?? '',
            $data['transactionStatus'] ?? '',
            (string) ($data['reasonCode'] ?? ''),
        ];

        $signatureString = implode(';', $signatureFields);
        $generatedSignature = hash_hmac('md5', $signatureString, $this->secretKey);

        \Log::info('WayForPay callback signature verification', [
            'fields' => $signatureFields,
            'signature_string' => $signatureString,
            'secret_key_length' => strlen($this->secretKey),
            'secret_key_first_chars' => substr($this->secretKey, 0, 5),
            'generated_signature' => $generatedSignature,
            'provided_signature' => $data['merchantSignature'] ?? '',
            'signatures_match' => $generatedSignature === ($data['merchantSignature'] ?? ''),
        ]);

        $isValid = PaymentSignature::verify(
            $signatureFields,
            $this->secretKey,
            $data['merchantSignature'] ?? ''
        );

        \Log::info('WayForPay signature verification result', [
            'is_valid' => $isValid,
        ]);

        return $isValid;
    }

    public function parseCallback(array $data): PaymentCallbackData
    {
        return PaymentCallbackData::from($data);
    }

    public function generateCallbackResponse(PaymentCallbackData $callback): array
    {
        $time = now()->timestamp;
        $status = $callback->transactionStatus === 'Approved' ? 'accept' : 'decline';

        $signatureFields = [
            $callback->orderReference,
            $status,
            $time,
        ];

        $signature = PaymentSignature::generate($signatureFields, $this->secretKey);

        return [
            'orderReference' => $callback->orderReference,
            'status' => $status,
            'time' => $time,
            'signature' => $signature->value,
        ];
    }

    private function getProductsFromTransaction(Transaction $transaction): \Illuminate\Support\Collection
    {
        $purchasable = $transaction->purchasable;

        return collect([
            PaymentProductData::from([
                'name' => $purchasable->title ?? 'Course',
                'price' => $transaction->amount,
                'count' => 1,
            ]),
        ]);
    }
}
