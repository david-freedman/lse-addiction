<?php

namespace App\Domains\Customer\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

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
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'verified_at' => 'datetime',
        'created_at' => 'datetime',
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
            'code' => Str::upper(Str::random(6)),
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
}
