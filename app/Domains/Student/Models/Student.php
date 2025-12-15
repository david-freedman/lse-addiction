<?php

namespace App\Domains\Student\Models;

use App\Domains\Certificate\Models\Certificate;
use App\Domains\Course\Models\Course;
use App\Domains\Course\Models\CourseStudent;
use App\Domains\Progress\Models\StudentCourseProgress;
use App\Domains\Progress\Models\StudentLessonProgress;
use App\Domains\Progress\Models\StudentModuleProgress;
use App\Domains\Progress\Models\StudentQuizAttempt;
use App\Domains\Shared\Casts\EmailCast;
use App\Domains\Shared\Casts\PhoneCast;
use App\Domains\Transaction\Models\Transaction;
use App\Domains\Verification\Models\Verification;
use App\Domains\Webinar\Models\Webinar;
use App\Domains\Webinar\Models\WebinarStudent;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Student extends Authenticatable
{
    use Notifiable, SoftDeletes;

    protected $fillable = [
        'email',
        'phone',
        'name',
        'surname',
        'birthday',
        'city',
        'profile_photo',
        'email_verified_at',
        'phone_verified_at',
        'last_login_at',
    ];

    protected $casts = [
        'email' => EmailCast::class,
        'phone' => PhoneCast::class,
        'birthday' => 'date',
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function verifications(): MorphMany
    {
        return $this->morphMany(Verification::class, 'verifiable');
    }

    public function profileFieldValues(): HasMany
    {
        return $this->hasMany(StudentProfileFieldValue::class);
    }

    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class)
            ->using(CourseStudent::class)
            ->withPivot(['enrolled_at', 'status']);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(StudentGroup::class, 'student_group_members', 'student_id', 'group_id')
            ->withPivot('added_at');
    }

    public function lessonProgress(): HasMany
    {
        return $this->hasMany(StudentLessonProgress::class);
    }

    public function moduleProgress(): HasMany
    {
        return $this->hasMany(StudentModuleProgress::class);
    }

    public function courseProgress(): HasMany
    {
        return $this->hasMany(StudentCourseProgress::class);
    }

    public function quizAttempts(): HasMany
    {
        return $this->hasMany(StudentQuizAttempt::class);
    }

    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class);
    }

    public function webinars(): BelongsToMany
    {
        return $this->belongsToMany(Webinar::class, 'webinar_student')
            ->using(WebinarStudent::class)
            ->withPivot(['registered_at', 'attended_at', 'cancelled_at']);
    }

    public function markEmailAsVerified(): void
    {
        $this->email_verified_at = now();
        $this->save();
    }

    public function markPhoneAsVerified(): void
    {
        $this->phone_verified_at = now();
        $this->save();
    }

    public function hasVerifiedEmail(): bool
    {
        return $this->email_verified_at !== null;
    }

    public function hasVerifiedPhone(): bool
    {
        return $this->phone_verified_at !== null;
    }

    public function isFullyVerified(): bool
    {
        $requireEmail = config('verification.require_email', true);
        $requirePhone = config('verification.require_phone', true);

        $emailVerified = ! $requireEmail || $this->hasVerifiedEmail();
        $phoneVerified = ! $requirePhone || $this->hasVerifiedPhone();

        return $emailVerified && $phoneVerified;
    }

    public function hasContactDetails(): bool
    {
        return $this->name !== null
            && $this->surname !== null
            && $this->birthday !== null
            && $this->city !== null;
    }

    public function hasPurchasedCourse(Course $course): bool
    {
        return $this->transactions()
            ->where('purchasable_type', Course::class)
            ->where('purchasable_id', $course->id)
            ->where('status', \App\Domains\Transaction\Enums\TransactionStatus::Completed)
            ->exists();
    }

    public function hasAccessToCourse(Course $course): bool
    {
        return $this->courses()->where('course_id', $course->id)->exists();
    }

    public function getProfilePhotoUrlAttribute(): ?string
    {
        if (! $this->profile_photo) {
            return null;
        }

        return asset('storage/'.$this->profile_photo);
    }

    public function getInitialsAttribute(): string
    {
        $firstInitial = $this->name ? mb_strtoupper(mb_substr($this->name, 0, 1)) : '';
        $lastInitial = $this->surname ? mb_strtoupper(mb_substr($this->surname, 0, 1)) : '';

        return $firstInitial.$lastInitial;
    }

    public function hasProfilePhoto(): bool
    {
        return $this->profile_photo !== null;
    }

    public function isNew(): bool
    {
        return $this->created_at->diffInDays(now()) <= 7;
    }
}
