<?php

namespace App\Domains\Payment\Data;

use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;

class PaymentCallbackData extends Data
{
    public function __construct(
        #[Required, StringType]
        public readonly string $merchantAccount,
        #[Required, StringType]
        public readonly string $orderReference,
        #[Required]
        public readonly float $amount,
        #[Required, StringType]
        public readonly string $currency,
        #[Required, StringType]
        public readonly string $authCode,
        #[Nullable, StringType]
        public readonly ?string $email = null,
        #[Nullable, StringType]
        public readonly ?string $phone = null,
        #[Required, StringType]
        public readonly string $transactionStatus,
        #[Required, StringType]
        public readonly string $cardPan,
        #[Nullable, StringType]
        public readonly ?string $cardType = null,
        #[Required, StringType]
        public readonly string $reasonCode,
        #[Required, StringType]
        public readonly string $merchantSignature,
        #[Nullable, StringType]
        public readonly ?string $recToken = null,
        #[Nullable, StringType]
        public readonly ?string $fee = null,
        #[Nullable]
        public readonly ?int $createdDate = null,
        #[Nullable]
        public readonly ?int $processingDate = null,
        #[Nullable, StringType]
        public readonly ?string $paymentSystem = null,
        #[Nullable, StringType]
        public readonly ?string $issuerBankCountry = null,
        #[Nullable, StringType]
        public readonly ?string $issuerBankName = null,
        #[Nullable, StringType]
        public readonly ?string $cardBin = null,
        #[Nullable, StringType]
        public readonly ?string $reason = null,
        #[Nullable, StringType]
        public readonly ?string $repayUrl = null,
    ) {}
}
