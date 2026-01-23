<?php

namespace App\Domains\Certificate\Models;

use App\Domains\Certificate\Enums\CertificateGrade;
use App\Domains\Certificate\Enums\CertificateStatus;
use App\Domains\Course\Models\Course;
use App\Domains\Student\Models\Student;
use App\Models\User;
use Database\Factories\CertificateFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Certificate extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected static function newFactory(): CertificateFactory
    {
        return CertificateFactory::new();
    }

    protected $fillable = [
        'certificate_number',
        'student_id',
        'course_id',
        'grade',
        'study_hours',
        'issued_at',
        'viewed_at',
        'issued_by',
        'published_at',
        'published_by',
        'revoked_at',
        'revoked_by',
    ];

    protected function casts(): array
    {
        return [
            'grade' => 'decimal:2',
            'issued_at' => 'datetime',
            'viewed_at' => 'datetime',
            'published_at' => 'datetime',
            'revoked_at' => 'datetime',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function issuedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    public function publishedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'published_by');
    }

    public function revokedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'revoked_by');
    }

    protected function gradeLevel(): Attribute
    {
        return Attribute::make(
            get: fn () => CertificateGrade::fromScore($this->grade)
        );
    }

    protected function formattedStudyHours(): Attribute
    {
        return Attribute::make(
            get: function () {
                $hours = intdiv($this->study_hours, 60);

                return $hours.' год';
            }
        );
    }

    protected function formattedIssuedAt(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->issued_at->locale('uk')->isoFormat('D MMMM YYYY').' р.'
        );
    }

    public function isAutoIssued(): bool
    {
        return $this->issued_by === null;
    }

    public function isPending(): bool
    {
        return $this->published_at === null && $this->revoked_at === null && !$this->trashed();
    }

    public function isPublished(): bool
    {
        return $this->published_at !== null && $this->revoked_at === null && !$this->trashed();
    }

    public function isRevoked(): bool
    {
        return $this->revoked_at !== null || $this->trashed();
    }

    public function isVisibleToStudent(): bool
    {
        return $this->isPublished();
    }

    public function getStatus(): CertificateStatus
    {
        if ($this->isRevoked()) {
            return CertificateStatus::Revoked;
        }

        if ($this->isPublished()) {
            return CertificateStatus::Published;
        }

        return CertificateStatus::Pending;
    }

    public static function generateNumber(Course $course, Student $student): string
    {
        return sprintf('%d-2556-%s-%s', now()->year, $course->number, $student->number);
    }

    public function scopeForStudent($query, int $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    public function scopeSearch($query, ?string $search)
    {
        if (!$search) {
            return $query;
        }

        return $query->whereHas('course', function ($q) use ($search) {
            $q->where('name', 'ilike', "%{$search}%");
        });
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->whereNull('published_at')
            ->whereNull('revoked_at')
            ->whereNull('deleted_at');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->whereNotNull('published_at')
            ->whereNull('revoked_at')
            ->whereNull('deleted_at');
    }

    public function scopeRevoked(Builder $query): Builder
    {
        return $query->where(function ($q) {
            $q->whereNotNull('revoked_at')
                ->orWhereNotNull('deleted_at');
        });
    }

    public function scopeVisibleToStudent(Builder $query): Builder
    {
        return $query->published();
    }
}
