<?php

namespace App\Domains\Student\ViewModels;

use App\Domains\Student\Models\Student;

readonly class StudentProfileViewModel
{
    public function __construct(
        private Student $student
    ) {}

    public function email(): string
    {
        return $this->student->email->value;
    }

    public function phone(): string
    {
        return $this->student->phone->value;
    }

    public function isEmailVerified(): bool
    {
        return $this->student->hasVerifiedEmail();
    }

    public function isPhoneVerified(): bool
    {
        return $this->student->hasVerifiedPhone();
    }

    public function isFullyVerified(): bool
    {
        return $this->student->isFullyVerified();
    }

    public function emailVerifiedAt(): ?string
    {
        return $this->student->email_verified_at?->format('Y-m-d H:i');
    }

    public function phoneVerifiedAt(): ?string
    {
        return $this->student->phone_verified_at?->format('Y-m-d H:i');
    }

    public function surname(): ?string
    {
        return $this->student->surname;
    }

    public function name(): ?string
    {
        return $this->student->name;
    }

    public function birthday(): ?string
    {
        return $this->student->birthday?->format('d.m.Y');
    }

    public function birthdayForInput(): ?string
    {
        return $this->student->birthday?->format('Y-m-d');
    }

    public function city(): ?string
    {
        return $this->student->city;
    }

    public function hasContactDetails(): bool
    {
        return $this->student->hasContactDetails();
    }

    public function profileFields(): array
    {
        $values = $this->student->profileFieldValues()->with('profileField')->get();

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
        return $this->student->profile_photo_url;
    }

    public function initials(): string
    {
        return $this->student->initials;
    }

    public function hasProfilePhoto(): bool
    {
        return $this->student->hasProfilePhoto();
    }
}
