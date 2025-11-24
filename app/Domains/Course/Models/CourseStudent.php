<?php

namespace App\Domains\Course\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class CourseStudent extends Pivot
{
    protected $table = 'course_student';

    protected $casts = [
        'enrolled_at' => 'datetime',
        'individual_discount' => 'decimal:2',
        'last_activity_at' => 'datetime',
    ];

    public $timestamps = false;

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function formattedProgress(): string
    {
        return "{$this->lessons_completed}/{$this->total_lessons}";
    }

    public function progressPercentage(): int
    {
        if ($this->total_lessons === 0) {
            return 0;
        }

        return (int) round(($this->lessons_completed / $this->total_lessons) * 100);
    }
}
