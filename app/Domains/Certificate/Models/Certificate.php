<?php

namespace App\Domains\Certificate\Models;

use App\Domains\Certificate\Enums\CertificateGrade;
use App\Domains\Course\Models\Course;
use App\Domains\Student\Models\Student;
use App\Models\User;
use Database\Factories\CertificateFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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
    ];

    protected function casts(): array
    {
        return [
            'grade' => 'decimal:2',
            'issued_at' => 'datetime',
            'viewed_at' => 'datetime',
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

    public function isRevoked(): bool
    {
        return $this->trashed();
    }

    public static function generateNumber(): string
    {
        $year = now()->year;
        $lastCertificate = self::withTrashed()
            ->where('certificate_number', 'like', "LSE-{$year}-%")
            ->orderByRaw("CAST(SPLIT_PART(certificate_number, '-', 3) AS INTEGER) DESC")
            ->first();

        $sequence = 1;
        if ($lastCertificate) {
            $parts = explode('-', $lastCertificate->certificate_number);
            $sequence = (int) end($parts) + 1;
        }

        return sprintf('LSE-%d-%03d', $year, $sequence);
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
}
