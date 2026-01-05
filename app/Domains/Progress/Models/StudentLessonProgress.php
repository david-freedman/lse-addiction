<?php

namespace App\Domains\Progress\Models;

use App\Domains\Lesson\Models\Lesson;
use App\Domains\Progress\Enums\ProgressStatus;
use App\Domains\Student\Models\Student;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentLessonProgress extends Model
{
    protected $table = 'student_lesson_progress';

    protected $fillable = [
        'student_id',
        'lesson_id',
        'status',
        'started_at',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => ProgressStatus::class,
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
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
