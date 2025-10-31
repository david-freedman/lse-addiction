<?php

namespace App\Domains\Customer\ViewModels;

use App\Domains\Customer\Models\Customer;

readonly class CustomerProfileViewModel
{
    public function __construct(
        private Customer $customer
    ) {}

    public function email(): string
    {
        return $this->customer->email->value;
    }

    public function phone(): string
    {
        return $this->customer->phone->value;
    }

    public function isEmailVerified(): bool
    {
        return $this->customer->hasVerifiedEmail();
    }

    public function isPhoneVerified(): bool
    {
        return $this->customer->hasVerifiedPhone();
    }

    public function isFullyVerified(): bool
    {
        return $this->customer->isFullyVerified();
    }

    public function emailVerifiedAt(): ?string
    {
        return $this->customer->email_verified_at?->format('Y-m-d H:i');
    }

    public function phoneVerifiedAt(): ?string
    {
        return $this->customer->phone_verified_at?->format('Y-m-d H:i');
    }
}
