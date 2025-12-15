<?php

namespace App\Domains\Webinar\Models;

use App\Domains\Student\Models\Student;
use App\Domains\Teacher\Models\Teacher;
use App\Domains\Webinar\Enums\WebinarStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Webinar extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'banner',
        'teacher_id',
        'starts_at',
        'duration_minutes',
        'meeting_url',
        'status',
        'max_participants',
        'price',
        'old_price',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'status' => WebinarStatus::class,
            'duration_minutes' => 'integer',
            'max_participants' => 'integer',
            'price' => 'decimal:2',
            'old_price' => 'decimal:2',
        ];
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'webinar_student')
            ->using(WebinarStudent::class)
            ->withPivot(['registered_at', 'attended_at', 'cancelled_at', 'transaction_id']);
    }

    public function activeRegistrations(): BelongsToMany
    {
        return $this->students()->wherePivotNull('cancelled_at');
    }

    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->where('status', WebinarStatus::Upcoming)
            ->where('starts_at', '>', now());
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('starts_at');
    }

    public function scopeInMonth(Builder $query, int $year, int $month): Builder
    {
        return $query->whereYear('starts_at', $year)
            ->whereMonth('starts_at', $month);
    }

    public function scopeOnDate(Builder $query, Carbon $date): Builder
    {
        return $query->whereDate('starts_at', $date);
    }

    public function scopeForStudent(Builder $query, int $studentId): Builder
    {
        return $query->whereHas('students', function ($q) use ($studentId) {
            $q->where('student_id', $studentId)
                ->whereNull('webinar_student.cancelled_at');
        });
    }

    public function scopeNotCancelled(Builder $query): Builder
    {
        return $query->where('status', '!=', WebinarStatus::Cancelled);
    }

    protected function bannerUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->banner
                ? (str_starts_with($this->banner, 'http://') || str_starts_with($this->banner, 'https://'))
                    ? $this->banner
                    : Storage::disk('public')->url($this->banner)
                : null
        );
    }

    public function participantsCount(): int
    {
        return $this->activeRegistrations()->count();
    }

    public function getIsStartingSoonAttribute(): bool
    {
        if ($this->status !== WebinarStatus::Upcoming) {
            return false;
        }

        return $this->starts_at->diffInMinutes(now(), absolute: true) <= 30
            && $this->starts_at->isFuture();
    }

    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->title
        );
    }

    protected function endsAt(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->starts_at?->addMinutes($this->duration_minutes)
        );
    }

    protected function isFree(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->price == 0
        );
    }

    public function hasCapacity(): bool
    {
        if ($this->max_participants === null) {
            return true;
        }

        return $this->participantsCount() < $this->max_participants;
    }

    public function getAvailableSpotsAttribute(): ?int
    {
        if ($this->max_participants === null) {
            return null;
        }

        return max(0, $this->max_participants - $this->participantsCount());
    }

    public function getFormattedDateAttribute(): string
    {
        return $this->starts_at->translatedFormat('j F Y') . ' р.';
    }

    public function getFormattedTimeAttribute(): string
    {
        return $this->starts_at->format('H:i');
    }

    public function getFormattedDurationAttribute(): string
    {
        $hours = intdiv($this->duration_minutes, 60);
        $minutes = $this->duration_minutes % 60;

        if ($hours > 0 && $minutes > 0) {
            return "{$hours} год {$minutes} хв";
        }

        if ($hours > 0) {
            return "{$hours} " . trans_choice('година|години|годин', $hours);
        }

        return "{$minutes} хв";
    }

    public function isRegistered(Student $student): bool
    {
        return $this->activeRegistrations()
            ->where('student_id', $student->id)
            ->exists();
    }

    public function canUnregister(): bool
    {
        if (!$this->starts_at) {
            return false;
        }

        return $this->starts_at->diffInHours(now()) >= 24;
    }

    public function getMeetingUrlForStudent(Student $student): ?string
    {
        if (!$this->isRegistered($student)) {
            return null;
        }

        if (!$this->meeting_url) {
            return null;
        }

        $minutesUntilStart = $this->starts_at->diffInMinutes(now(), false);

        if ($minutesUntilStart >= -30 || $this->status === WebinarStatus::Live) {
            return $this->meeting_url;
        }

        return null;
    }
}
