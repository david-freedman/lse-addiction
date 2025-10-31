<?php

namespace App\Domains\ActivityLog\Models;

use App\Domains\ActivityLog\Enums\ActivitySubject;
use App\Domains\ActivityLog\Enums\ActivityType;
use App\Domains\Customer\Models\Customer;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    public const UPDATED_AT = null;

    protected $fillable = [
        'subject_type',
        'subject_id',
        'activity_type',
        'description',
        'properties',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'subject_type' => ActivitySubject::class,
        'activity_type' => ActivityType::class,
        'properties' => 'array',
        'created_at' => 'datetime',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'subject_id')
            ->where('subject_type', ActivitySubject::Customer);
    }
}
