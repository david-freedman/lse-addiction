<?php

namespace App\Domains\Customer\Models;

use App\Domains\Customer\Enums\ProfileFieldType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProfileField extends Model
{
    protected $fillable = [
        'key',
        'type',
        'label',
        'options',
        'is_required',
        'order',
        'is_active',
    ];

    protected $casts = [
        'options' => 'array',
        'is_required' => 'boolean',
        'is_active' => 'boolean',
        'order' => 'integer',
        'type' => ProfileFieldType::class,
    ];

    public function customerValues(): HasMany
    {
        return $this->hasMany(CustomerProfileFieldValue::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}
