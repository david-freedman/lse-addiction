<?php

namespace App\Domains\Customer\Data;

use App\Domains\Shared\Casts\EmailDataCast;
use App\Domains\Shared\Casts\PhoneDataCast;
use App\Domains\Shared\Rules\ValidEmail;
use App\Domains\Shared\Rules\ValidPhone;
use App\Domains\Shared\ValueObjects\Email;
use App\Domains\Shared\ValueObjects\Phone;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\Rule;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Attributes\Validation\Unique;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Data;

class RegisterCustomerData extends Data
{
    public function __construct(
        #[Required, StringType]
        #[Rule(new ValidEmail)]
        #[Unique('customers', 'email')]
        #[WithCast(EmailDataCast::class)]
        public readonly Email $email,
        #[Required, StringType]
        #[Rule(new ValidPhone)]
        #[Unique('customers', 'phone')]
        #[WithCast(PhoneDataCast::class)]
        public readonly Phone $phone,
    ) {}
}
