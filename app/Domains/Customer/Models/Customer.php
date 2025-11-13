<?php

namespace App\Domains\Customer\Models;

use App\Domains\Course\Models\Course;
use App\Domains\Course\Models\CourseCustomer;
use App\Domains\Shared\Casts\EmailCast;
use App\Domains\Shared\Casts\PhoneCast;
use App\Domains\Transaction\Models\Transaction;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Customer extends Authenticatable
{
    protected $fillable = [
        'email',
        'phone',
        'name',
        'surname',
        'birthday',
        'city',
        'email_verified_at',
        'phone_verified_at',
    ];

    protected $casts = [
        'email' => EmailCast::class,
        'phone' => PhoneCast::class,
        'birthday' => 'date',
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
    ];

    public function verifications(): HasMany
    {
        return $this->hasMany(CustomerVerification::class);
    }

    public function profileFieldValues(): HasMany
    {
        return $this->hasMany(CustomerProfileFieldValue::class);
    }

    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class)
            ->using(CourseCustomer::class)
            ->withPivot(['enrolled_at', 'status']);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function markEmailAsVerified(): void
    {
        $this->email_verified_at = now();
        $this->save();
    }

    public function markPhoneAsVerified(): void
    {
        $this->phone_verified_at = now();
        $this->save();
    }

    public function hasVerifiedEmail(): bool
    {
        return $this->email_verified_at !== null;
    }

    public function hasVerifiedPhone(): bool
    {
        return $this->phone_verified_at !== null;
    }

    public function isFullyVerified(): bool
    {
        return $this->hasVerifiedEmail() && $this->hasVerifiedPhone();
    }

    public function hasContactDetails(): bool
    {
        return $this->name !== null
            && $this->surname !== null
            && $this->birthday !== null
            && $this->city !== null;
    }
}
