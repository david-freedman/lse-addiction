<?php

namespace App\Domains\Payment\Data;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Attributes\Validation\Url;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class PaymentRequestData extends Data
{
    public function __construct(
        #[Required, StringType]
        public readonly string $merchantAccount,
        #[Required, StringType]
        public readonly string $merchantDomainName,
        #[Required, StringType]
        public readonly string $merchantSignature,
        #[Required, StringType]
        public readonly string $orderReference,
        #[Required]
        public readonly int $orderDate,
        #[Required]
        public readonly float $amount,
        #[Required, StringType]
        public readonly string $currency,
        #[Required, DataCollectionOf(PaymentProductData::class)]
        public readonly DataCollection $products,
        #[Required, StringType]
        public readonly string $merchantTransactionSecureType,
        #[Required, Url]
        public readonly string $returnUrl,
        #[Required, Url]
        public readonly string $serviceUrl,
        #[Required, StringType]
        public readonly string $clientFirstName,
        #[Required, StringType]
        public readonly string $clientLastName,
        #[Required, StringType]
        public readonly string $clientPhone,
        #[Required, StringType]
        public readonly string $clientEmail,
        #[Nullable, StringType]
        public readonly ?string $language = 'UA',
        #[Nullable]
        public readonly ?int $apiVersion = 2,
        #[Nullable, StringType]
        public readonly ?string $clientAccountId = null,
        #[Nullable]
        public readonly ?int $orderTimeout = null,
        #[Nullable, StringType]
        public readonly ?string $paymentSystems = null,
        #[Nullable, StringType]
        public readonly ?string $defaultPaymentSystem = null,
        #[Nullable, Url]
        public readonly ?string $declineUrl = null,
    ) {}
}
