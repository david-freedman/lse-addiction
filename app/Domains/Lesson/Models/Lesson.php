<?php

namespace App\Domains\Lesson\Models;

use App\Domains\Homework\Models\Homework;
use App\Domains\Lesson\Enums\DicomSourceType;
use App\Domains\Lesson\Enums\LessonStatus;
use App\Domains\Lesson\Enums\LessonType;
use App\Domains\Module\Models\Module;
use App\Domains\Progress\Models\StudentLessonProgress;
use App\Domains\Quiz\Models\Quiz;
use Database\Factories\LessonFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Lesson extends Model
{
    use HasFactory;

    protected static function newFactory(): LessonFactory
    {
        return LessonFactory::new();
    }
    protected $fillable = [
        'module_id',
        'name',
        'description',
        'content',
        'type',
        'video_url',
        'dicom_source_type',
        'dicom_file_path',
        'dicom_url',
        'dicom_metadata',
        'qa_session_url',
        'duration_minutes',
        'order',
        'status',
        'attachments',
    ];

    protected function casts(): array
    {
        return [
            'type' => LessonType::class,
            'status' => LessonStatus::class,
            'dicom_source_type' => DicomSourceType::class,
            'dicom_metadata' => 'array',
            'attachments' => 'array',
        ];
    }

    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }

    public function quiz(): MorphOne
    {
        return $this->morphOne(Quiz::class, 'quizzable');
    }

    public function progress(): HasMany
    {
        return $this->hasMany(StudentLessonProgress::class);
    }

    public function notes(): HasMany
    {
        return $this->hasMany(StudentLessonNote::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(LessonComment::class)->whereNull('parent_id');
    }

    public function homework(): HasOne
    {
        return $this->hasOne(Homework::class);
    }

    public function hasHomework(): bool
    {
        return $this->homework !== null;
    }

    public function hasRequiredHomework(): bool
    {
        return $this->homework?->is_required ?? false;
    }

    public function scopePublished($query)
    {
        return $query->where('status', LessonStatus::Published);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    public function scopeByType($query, LessonType $type)
    {
        return $query->where('type', $type);
    }

    protected function isVideo(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->type === LessonType::Video
        );
    }

    protected function isQuiz(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->type === LessonType::Quiz
        );
    }

    protected function isDicom(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->type === LessonType::Dicom
        );
    }

    protected function isQaSession(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->type === LessonType::QaSession
        );
    }

    protected function isSurvey(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->type === LessonType::Survey
        );
    }

    protected function formattedDuration(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (!$this->duration_minutes) {
                    return null;
                }

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
        );
    }
}
