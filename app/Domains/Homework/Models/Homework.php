<?php

namespace App\Domains\Homework\Models;

use App\Domains\Homework\Enums\HomeworkResponseType;
use App\Domains\Homework\Enums\HomeworkSubmissionStatus;
use App\Domains\Lesson\Models\Lesson;
use Database\Factories\HomeworkFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Homework extends Model
{
    use HasFactory;

    protected static function newFactory(): HomeworkFactory
    {
        return HomeworkFactory::new();
    }

    protected $table = 'homeworks';

    protected $fillable = [
        'lesson_id',
        'description',
        'response_type',
        'max_points',
        'passing_score',
        'max_attempts',
        'deadline_at',
        'is_required',
        'allowed_extensions',
        'max_file_size_mb',
        'max_files',
    ];

    protected function casts(): array
    {
        return [
            'response_type' => HomeworkResponseType::class,
            'is_required' => 'boolean',
            'deadline_at' => 'datetime',
            'allowed_extensions' => 'array',
        ];
    }

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(HomeworkSubmission::class);
    }

    public function scopeRequired(Builder $query): Builder
    {
        return $query->where('is_required', true);
    }

    public function hasDeadline(): bool
    {
        return $this->deadline_at !== null;
    }

    public function isDeadlinePassed(): bool
    {
        return $this->hasDeadline() && now()->gt($this->deadline_at);
    }

    public function getPassingScorePoints(): ?int
    {
        if ($this->passing_score === null) {
            return null;
        }

        return (int) ceil($this->max_points * $this->passing_score / 100);
    }

    public function allowsMoreAttempts(int $currentAttempts): bool
    {
        if ($this->max_attempts === null) {
            return true;
        }

        return $currentAttempts < $this->max_attempts;
    }

    public function getStudentAttempts(int $studentId): int
    {
        return $this->submissions()
            ->where('student_id', $studentId)
            ->count();
    }

    public function getLatestSubmission(int $studentId): ?HomeworkSubmission
    {
        return $this->submissions()
            ->where('student_id', $studentId)
            ->latest('attempt_number')
            ->first();
    }

    public function hasApprovedSubmission(int $studentId): bool
    {
        $latestSubmission = $this->getLatestSubmission($studentId);

        if (!$latestSubmission) {
            return false;
        }

        if ($latestSubmission->status !== HomeworkSubmissionStatus::Approved) {
            return false;
        }

        $passingPoints = $this->getPassingScorePoints();

        if ($passingPoints !== null && $latestSubmission->score < $passingPoints) {
            return false;
        }

        return true;
    }

    public function canStudentSubmit(int $studentId): bool
    {
        $latestSubmission = $this->getLatestSubmission($studentId);

        if (!$latestSubmission) {
            return true;
        }

        if ($latestSubmission->status === HomeworkSubmissionStatus::Approved) {
            return false;
        }

        if ($latestSubmission->status === HomeworkSubmissionStatus::Pending) {
            return false;
        }

        $attempts = $this->getStudentAttempts($studentId);

        return $this->allowsMoreAttempts($attempts);
    }
}
