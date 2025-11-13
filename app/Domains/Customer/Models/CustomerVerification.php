<?php

namespace App\Domains\Customer\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerVerification extends Model
{
    public const UPDATED_AT = null;

    protected $fillable = [
        'customer_id',
        'type',
        'contact',
        'code',
        'expires_at',
        'verified_at',
        'purpose',
        'last_sent_at',
        'send_count',
        'hourly_reset_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'verified_at' => 'datetime',
        'created_at' => 'datetime',
        'last_sent_at' => 'datetime',
        'hourly_reset_at' => 'datetime',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public static function generate(
        ?int $customerId,
        string $type,
        string $contact,
        string $purpose
    ): self {
        return self::create([
            'customer_id' => $customerId,
            'type' => $type,
            'contact' => $contact,
            'code' => collect(range(1, 4))->map(fn () => random_int(1, 9))->implode(''),
            'expires_at' => now()->addMinutes(15),
            'purpose' => $purpose,
        ]);
    }

    public function isValid(): bool
    {
        return $this->verified_at === null && $this->expires_at->isFuture();
    }

    public function markAsVerified(): void
    {
        $this->verified_at = now();
        $this->save();
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->whereNull('verified_at');
    }

    public function scopeNotExpired(Builder $query): Builder
    {
        return $query->where('expires_at', '>', now());
    }

    public function canResend(): bool
    {
        if ($this->last_sent_at === null) {
            return true;
        }

        $intervalSeconds = (int) config('verification.resend_interval', 120);

        return $this->last_sent_at->addSeconds($intervalSeconds)->isPast();
    }

    public function isHourlyLimitReached(): bool
    {
        if ($this->hourly_reset_at === null || $this->hourly_reset_at->isPast()) {
            return false;
        }

        $hourlyLimit = config('verification.hourly_limit', 5);

        return $this->send_count >= $hourlyLimit;
    }

    public function getSecondsUntilResend(): int
    {
        if ($this->last_sent_at === null) {
            return 0;
        }

        $intervalSeconds = (int) config('verification.resend_interval', 120);
        $nextAllowedAt = $this->last_sent_at->addSeconds($intervalSeconds);

        return max(0, $nextAllowedAt->diffInSeconds(now(), false) * -1);
    }

    public function incrementSendCount(): void
    {
        if ($this->hourly_reset_at === null || $this->hourly_reset_at->isPast()) {
            $this->hourly_reset_at = now()->addHour();
            $this->send_count = 1;
        } else {
            $this->send_count++;
        }

        $this->last_sent_at = now();
        $this->save();
    }
}
