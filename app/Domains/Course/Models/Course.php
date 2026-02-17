<?php

namespace App\Domains\Course\Models;

use App\Domains\Course\Enums\CourseLabel;
use App\Domains\Course\Enums\CourseStatus;
use App\Domains\Course\Enums\CourseType;
use App\Domains\Lesson\Models\Lesson;
use App\Domains\Module\Models\Module;
use App\Domains\Quiz\Models\Quiz;
use App\Domains\Student\Models\Student;
use App\Domains\Teacher\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    use HasFactory;

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected $fillable = [
        'name',
        'slug',
        'number',
        'description',
        'description_short',
        'price',
        'old_price',
        'discount_percentage',
        'teacher_id',
        'banner',
        'author_id',
        'status',
        'type',
        'starts_at',
        'registration_starts_at',
        'registration_ends_at',
        'label',
        'is_sequential',
        'requires_certificate_approval',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'old_price' => 'decimal:2',
        'starts_at' => 'datetime',
        'registration_starts_at' => 'datetime',
        'registration_ends_at' => 'datetime',
        'type' => CourseType::class,
        'status' => CourseStatus::class,
        'is_sequential' => 'boolean',
        'requires_certificate_approval' => 'boolean',
    ];

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(CourseTag::class, 'course_tag', 'course_id', 'tag_id');
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class)
            ->using(CourseStudent::class)
            ->withPivot(['enrolled_at', 'status']);
    }

    public function modules(): HasMany
    {
        return $this->hasMany(Module::class)->orderBy('order');
    }

    public function isActive(): bool
    {
        return $this->status === CourseStatus::Active;
    }

    public function isHidden(): bool
    {
        return $this->status === CourseStatus::Hidden;
    }

    public function isDraft(): bool
    {
        return $this->status === CourseStatus::Draft;
    }

    public function isArchived(): bool
    {
        return $this->status === CourseStatus::Archived;
    }

    public function hasStudent(Student $student): bool
    {
        return $this->students()->where('student_id', $student->id)->exists();
    }

    /**
     * Effective registration end: explicitly set, or falls back to starts_at if only start is given.
     */
    public function effectiveRegistrationEndsAt(): ?\Illuminate\Support\Carbon
    {
        if ($this->registration_ends_at !== null) {
            return $this->registration_ends_at;
        }
        if ($this->registration_starts_at !== null) {
            return $this->starts_at;
        }

        return null;
    }

    public function isAvailableByDate(): bool
    {
        // Both fields must be set (or implicitly derived) for registration to be available
        if ($this->registration_starts_at === null) {
            return false;
        }

        $endsAt = $this->effectiveRegistrationEndsAt();

        if ($this->registration_starts_at->isFuture()) {
            return false;
        }
        if ($endsAt !== null && $endsAt->isPast()) {
            return false;
        }

        return true;
    }

    public function scopeAvailableForPurchase($query, ?Student $student = null)
    {
        $query->where('status', CourseStatus::Active)
            ->where('registration_starts_at', '<=', now())
            ->where(function ($q) {
                // registration_ends_at is set and not expired
                $q->where('registration_ends_at', '>=', now())
                  // or only registration_starts_at is set — use starts_at as implicit end
                  ->orWhere(function ($sq) {
                      $sq->whereNull('registration_ends_at')
                          ->where(function ($inner) {
                              $inner->whereNull('starts_at')
                                  ->orWhere('starts_at', '>=', now());
                          });
                  });
            });

        if ($student) {
            $query->whereDoesntHave('students', fn ($q) =>
                $q->where('student_id', $student->id)
            );
        }

        return $query;
    }

    public function scopeFeatured($query)
    {
        return $query->where('type', 'featured');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('type', CourseType::Upcoming->value);
    }

    public function scopeRecorded($query)
    {
        return $query->where('type', CourseType::Recorded->value);
    }

    public function scopeFree($query)
    {
        return $query->where('price', 0);
    }

    public function scopeByFilter($query, ?CourseType $filter)
    {
        if ($filter === null) {
            return $query;
        }

        return match ($filter) {
            CourseType::Upcoming => $query->upcoming(),
            CourseType::Recorded => $query->recorded(),
            CourseType::Free => $query->free(),
        };
    }

    protected function formattedDate(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->starts_at
                ? $this->starts_at->locale('uk')->isoFormat('D MMMM')
                : null
        );
    }

    protected function formattedRegistrationPeriod(): Attribute
    {
        return Attribute::make(
            get: function () {
                $start = $this->registration_starts_at;
                $end = $this->effectiveRegistrationEndsAt();

                if (! $start) {
                    return null;
                }

                if ($end) {
                    return $start->locale('uk')->isoFormat('D MMMM').' — '.$end->locale('uk')->isoFormat('D MMMM');
                }

                return 'з '.$start->locale('uk')->isoFormat('D MMMM');
            }
        );
    }

    protected function hasDiscount(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->old_price !== null && $this->old_price > $this->price
        );
    }

    protected function discountAmount(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->has_discount
                ? $this->old_price - $this->price
                : 0
        );
    }

    protected function formattedPrice(): Attribute
    {
        return Attribute::make(
            get: fn () => number_format($this->price, 0, ',', ' ').' ₴'
        );
    }

    protected function formattedOldPrice(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->old_price
                ? number_format($this->old_price, 0, ',', ' ').' ₴'
                : null
        );
    }

    protected function formattedDiscountAmount(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->has_discount
                ? number_format($this->discount_amount, 0, ',', ' ').' ₴'
                : null
        );
    }

    protected function bannerUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->banner
                ? (str_starts_with($this->banner, 'http://') || str_starts_with($this->banner, 'https://'))
                    ? $this->banner
                    : \Storage::disk('public')->url($this->banner)
                : null
        );
    }

    protected function labelText(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->label
                ? CourseLabel::tryFrom($this->label)?->label()
                : null
        );
    }

    protected function modulesCount(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->modules()->count()
        );
    }

    protected function lessonsCount(): Attribute
    {
        return Attribute::make(
            get: fn () => Lesson::whereIn('module_id', $this->modules()->pluck('id'))->count()
        );
    }

    protected function totalDuration(): Attribute
    {
        return Attribute::make(
            get: fn () => Lesson::whereIn('module_id', $this->modules()->pluck('id'))
                ->sum('duration_minutes')
        );
    }

    protected function formattedDuration(): Attribute
    {
        return Attribute::make(
            get: function () {
                $minutes = $this->total_duration;
                if (!$minutes) {
                    return null;
                }
                $hours = intdiv($minutes, 60);
                $mins = $minutes % 60;
                if ($hours && $mins) {
                    return "{$hours} год {$mins} хв";
                }
                if ($hours) {
                    return "{$hours} год";
                }

                return "{$mins} хв";
            }
        );
    }

    public function canBeEditedBy(User $user): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $this->teacher_id === $user->id || $this->author_id === $user->id;
    }

    public function requiresCertificateApproval(): bool
    {
        return $this->requires_certificate_approval;
    }

    public function hasFinalQuiz(): bool
    {
        $finalLessonIds = Lesson::whereIn('module_id', $this->modules()->pluck('id'))
            ->where('is_final', true)
            ->pluck('id');

        return Quiz::where('quizzable_type', Lesson::class)
            ->whereIn('quizzable_id', $finalLessonIds)
            ->exists();
    }

    public function getFinalQuiz(): ?Quiz
    {
        $finalLessonIds = Lesson::whereIn('module_id', $this->modules()->pluck('id'))
            ->where('is_final', true)
            ->pluck('id');

        return Quiz::where('quizzable_type', Lesson::class)
            ->whereIn('quizzable_id', $finalLessonIds)
            ->first();
    }
}
