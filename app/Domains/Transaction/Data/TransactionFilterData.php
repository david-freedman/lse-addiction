<?php

namespace App\Domains\Transaction\Data;

use App\Domains\Transaction\Enums\PaymentMethod;
use App\Domains\Transaction\Enums\TransactionStatus;
use Carbon\Carbon;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\Numeric;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Data;

class TransactionFilterData extends Data
{
    public function __construct(
        #[Nullable, StringType, Max(255)]
        public readonly ?string $search,

        #[Nullable]
        public readonly ?TransactionStatus $status,

        #[Nullable, Numeric, Exists('students', 'id')]
        public readonly ?int $student_id,

        #[Nullable, Numeric, Exists('courses', 'id')]
        public readonly ?int $course_id,

        #[Nullable]
        public readonly ?PaymentMethod $payment_method,

        #[Nullable, Numeric]
        public readonly ?float $amount_from,

        #[Nullable, Numeric]
        public readonly ?float $amount_to,

        #[Nullable, StringType, Max(255)]
        public readonly ?string $transaction_number,

        #[Nullable, WithCast(DateTimeInterfaceCast::class, format: 'd.m.Y')]
        public readonly ?Carbon $created_from,

        #[Nullable, WithCast(DateTimeInterfaceCast::class, format: 'd.m.Y')]
        public readonly ?Carbon $created_to,
    ) {}

    public static function rules(): array
    {
        return [];
    }
}
