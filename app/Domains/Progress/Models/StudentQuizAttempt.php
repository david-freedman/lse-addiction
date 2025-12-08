<?php

namespace App\Domains\Progress\Models;

use App\Domains\Quiz\Models\Quiz;
use App\Domains\Student\Models\Student;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentQuizAttempt extends Model
{
    protected $fillable = [
        'student_id',
        'quiz_id',
        'attempt_number',
        'score',
        'max_score',
        'passed',
        'answers',
        'time_spent_seconds',
        'started_at',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'passed' => 'boolean',
            'answers' => 'array',
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    protected function scorePercentage(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->max_score > 0
                ? round(($this->score / $this->max_score) * 100)
                : 0
        );
    }
}
