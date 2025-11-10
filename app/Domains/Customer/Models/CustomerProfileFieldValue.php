<?php

namespace App\Domains\Customer\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerProfileFieldValue extends Model
{
    protected $fillable = [
        'customer_id',
        'profile_field_id',
        'value',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function profileField(): BelongsTo
    {
        return $this->belongsTo(ProfileField::class);
    }
}
