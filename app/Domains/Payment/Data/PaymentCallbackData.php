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
        #[Required, StringType]
        public readonly string $email,
        #[Required, StringType]
        public readonly string $phone,
        #[Required, StringType]
        public readonly string $transactionStatus,
        #[Required, StringType]
        public readonly string $cardPan,
        #[Required, StringType]
        public readonly string $cardType,
        #[Required, StringType]
        public readonly string $reasonCode,
        #[Required, StringType]
        public readonly string $merchantSignature,
        #[Nullable, StringType]
        public readonly ?string $recToken = null,
        #[Nullable, StringType]
        public readonly ?string $fee = null,
    ) {}
}
