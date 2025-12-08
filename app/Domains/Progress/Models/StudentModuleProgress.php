<?php

namespace App\Domains\Progress\Models;

use App\Domains\Module\Models\Module;
use App\Domains\Progress\Enums\ProgressStatus;
use App\Domains\Student\Models\Student;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentModuleProgress extends Model
{
    protected $table = 'student_module_progress';

    protected $fillable = [
        'student_id',
        'module_id',
        'status',
        'lessons_completed',
        'total_lessons',
        'progress_percentage',
        'started_at',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => ProgressStatus::class,
            'progress_percentage' => 'decimal:2',
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }
}
