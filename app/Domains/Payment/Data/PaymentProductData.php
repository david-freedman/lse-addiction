<?php

namespace App\Domains\Payment\Data;

use Spatie\LaravelData\Attributes\Validation\Decimal;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;

class PaymentProductData extends Data
{
    public function __construct(
        #[Required, StringType]
        public readonly string $name,
        #[Required, Decimal(10, 2), Min(0.01)]
        public readonly float $price,
        #[Required, Min(1)]
        public readonly int $count,
    ) {}
}
