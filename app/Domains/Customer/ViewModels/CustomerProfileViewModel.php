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

    public function surname(): ?string
    {
        return $this->customer->surname;
    }

    public function name(): ?string
    {
        return $this->customer->name;
    }

    public function birthday(): ?string
    {
        return $this->customer->birthday?->format('d.m.Y');
    }

    public function birthdayForInput(): ?string
    {
        return $this->customer->birthday?->format('Y-m-d');
    }

    public function city(): ?string
    {
        return $this->customer->city;
    }

    public function hasContactDetails(): bool
    {
        return $this->customer->hasContactDetails();
    }

    public function profileFields(): array
    {
        $values = $this->customer->profileFieldValues()->with('profileField')->get();

        $result = [];
        foreach ($values as $value) {
            if ($value->profileField && $value->value) {
                $displayValue = $value->value;

                if ($value->profileField->type->value === 'select' && $value->profileField->options) {
                    $displayValue = $value->profileField->options[$value->value] ?? $value->value;
                }

                $result[$value->profileField->label] = $displayValue;
            }
        }

        return $result;
    }

    public function profilePhotoUrl(): ?string
    {
        return $this->customer->profile_photo_url;
    }

    public function initials(): string
    {
        return $this->customer->initials;
    }

    public function hasProfilePhoto(): bool
    {
        return $this->customer->hasProfilePhoto();
    }
}
