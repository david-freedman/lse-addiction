<?php

namespace App\Domains\Transaction\Data;

use App\Domains\Transaction\Enums\PaymentMethod;
use App\Domains\Transaction\Enums\TransactionStatus;
use Spatie\LaravelData\Attributes\Validation\Decimal;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;

class TransactionData extends Data
{
    public function __construct(
        #[Required]
        public readonly int $student_id,
        #[Required, StringType]
        public readonly string $purchasable_type,
        #[Required]
        public readonly int $purchasable_id,
        #[Required, Decimal(10, 2)]
        public readonly float $amount,
        #[StringType]
        public readonly string $currency = 'UAH',
        public readonly TransactionStatus $status = TransactionStatus::Pending,
        #[Nullable]
        public readonly ?PaymentMethod $payment_method = null,
        #[Nullable, StringType]
        public readonly ?string $payment_reference = null,
        #[Nullable]
        public readonly ?array $metadata = null,
    ) {}
}
