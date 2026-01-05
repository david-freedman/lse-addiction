<?php

namespace App\Domains\Discount\Models;

use App\Domains\Course\Models\Course;
use App\Domains\Discount\Enums\DiscountType;
use App\Domains\Student\Models\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentCourseDiscount extends Model
{
    protected $fillable = [
        'student_id',
        'course_id',
        'type',
        'value',
        'assigned_by',
        'used_at',
    ];

    protected $casts = [
        'type' => DiscountType::class,
        'value' => 'decimal:2',
        'used_at' => 'datetime',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function assignedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->whereNull('used_at');
    }

    public function scopeUsed(Builder $query): Builder
    {
        return $query->whereNotNull('used_at');
    }

    public function scopeForStudent(Builder $query, int $studentId): Builder
    {
        return $query->where('student_id', $studentId);
    }

    public function scopeForCourse(Builder $query, int $courseId): Builder
    {
        return $query->where('course_id', $courseId);
    }

    public function isActive(): bool
    {
        return $this->used_at === null;
    }

    public function calculateDiscountAmount(float $coursePrice): float
    {
        return match ($this->type) {
            DiscountType::Percentage => $coursePrice * ($this->value / 100),
            DiscountType::Fixed => min((float) $this->value, $coursePrice),
        };
    }

    public function formattedValue(): string
    {
        return $this->value.' '.$this->type->suffix();
    }
}
