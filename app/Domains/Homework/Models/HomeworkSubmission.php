<?php

namespace App\Domains\Homework\Models;

use App\Domains\Homework\Enums\HomeworkSubmissionStatus;
use App\Domains\Student\Models\Student;
use App\Models\User;
use Database\Factories\HomeworkSubmissionFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HomeworkSubmission extends Model
{
    use HasFactory;

    protected static function newFactory(): HomeworkSubmissionFactory
    {
        return HomeworkSubmissionFactory::new();
    }

    protected $fillable = [
        'homework_id',
        'student_id',
        'attempt_number',
        'text_response',
        'files',
        'status',
        'is_late',
        'score',
        'feedback',
        'reviewed_by',
        'reviewed_at',
        'submitted_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => HomeworkSubmissionStatus::class,
            'files' => 'array',
            'is_late' => 'boolean',
            'submitted_at' => 'datetime',
            'reviewed_at' => 'datetime',
        ];
    }

    public function homework(): BelongsTo
    {
        return $this->belongsTo(Homework::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function scopeForStudent(Builder $query, int $studentId): Builder
    {
        return $query->where('student_id', $studentId);
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', HomeworkSubmissionStatus::Pending);
    }

    public function scopeReviewed(Builder $query): Builder
    {
        return $query->whereIn('status', [
            HomeworkSubmissionStatus::Approved,
            HomeworkSubmissionStatus::Rejected,
            HomeworkSubmissionStatus::RevisionRequested,
        ]);
    }

    public function isPassed(): bool
    {
        if ($this->status !== HomeworkSubmissionStatus::Approved) {
            return false;
        }

        $passingPoints = $this->homework->getPassingScorePoints();

        if ($passingPoints === null) {
            return true;
        }

        return $this->score >= $passingPoints;
    }

    public function canEdit(): bool
    {
        return $this->status === HomeworkSubmissionStatus::Pending;
    }

    public function scorePercentage(): ?float
    {
        if ($this->score === null) {
            return null;
        }

        return round($this->score / $this->homework->max_points * 100, 1);
    }

    public function hasFiles(): bool
    {
        return !empty($this->files);
    }

    public function filesCount(): int
    {
        return $this->hasFiles() ? count($this->files) : 0;
    }

    public function getDeadlineDifferenceText(): ?string
    {
        $deadline = $this->homework->deadline_at;
        if (!$deadline || !$this->submitted_at) {
            return null;
        }

        $totalMinutes = $this->submitted_at->diffInMinutes($deadline, false);

        if ($totalMinutes > 0) {
            $days = (int) floor($totalMinutes / 1440);
            $hours = (int) floor(($totalMinutes % 1440) / 60);
            $minutes = $totalMinutes % 60;

            if ($days >= 1) {
                return "за {$days} " . trans_choice('день|дні|днів', $days) . " до дедлайну";
            }
            if ($hours >= 1) {
                return "за {$hours} год. до дедлайну";
            }
            return "за {$minutes} хв. до дедлайну";
        } else {
            $totalMinutes = abs($totalMinutes);
            $days = (int) floor($totalMinutes / 1440);
            $hours = (int) floor(($totalMinutes % 1440) / 60);
            $minutes = $totalMinutes % 60;

            if ($days >= 1) {
                return "{$days} " . trans_choice('день|дні|днів', $days) . " після дедлайну";
            }
            if ($hours >= 1) {
                return "{$hours} год. після дедлайну";
            }
            return "{$minutes} хв. після дедлайну";
        }
    }
}
