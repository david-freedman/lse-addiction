<?php

namespace App\Domains\Progress\Models;

use App\Domains\Lesson\Models\Lesson;
use App\Domains\Student\Models\Student;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentQuizDraft extends Model
{
    protected $fillable = [
        'student_id',
        'lesson_id',
        'answers',
        'started_at',
    ];

    protected function casts(): array
    {
        return [
            'answers' => 'array',
            'started_at' => 'datetime',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }
}
