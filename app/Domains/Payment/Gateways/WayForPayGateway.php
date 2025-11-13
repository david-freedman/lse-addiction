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
            $transaction->amount,
            $transaction->currency,
            ...$products->map(fn ($p) => $p->name)->toArray(),
            ...$products->map(fn ($p) => $p->count)->toArray(),
            ...$products->map(fn ($p) => $p->price)->toArray(),
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
            'clientFirstName' => $customer->first_name,
            'clientLastName' => $customer->last_name,
            'clientPhone' => $customer->phone ?? '',
            'clientEmail' => $customer->email,
        ]);
    }

    public function verifyCallback(array $data): bool
    {
        $signatureFields = [
            $data['orderReference'] ?? '',
            $data['merchantAccount'] ?? '',
            $data['amount'] ?? '',
            $data['currency'] ?? '',
            $data['authCode'] ?? '',
            $data['cardPan'] ?? '',
            $data['transactionStatus'] ?? '',
            $data['reasonCode'] ?? '',
        ];

        return PaymentSignature::verify(
            $signatureFields,
            $this->secretKey,
            $data['merchantSignature'] ?? ''
        );
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
