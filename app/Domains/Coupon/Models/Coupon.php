<?php

namespace App\Domains\Coupon\Models;

use App\Domains\Course\Models\Course;
use App\Domains\Student\Models\Student;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Coupon extends Model
{
    protected $fillable = [
        'code',
        'type',
        'value',
        'course_id',
        'valid_from',
        'valid_until',
        'max_uses',
        'times_used',
        'is_active',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'valid_from' => 'datetime',
        'valid_until' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class)
            ->withPivot(['assigned_at', 'used_at']);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function isValid(): bool
    {
        return $this->is_active
            && (!$this->valid_from || $this->valid_from <= now())
            && (!$this->valid_until || $this->valid_until >= now())
            && (!$this->max_uses || $this->times_used < $this->max_uses);
    }
}
