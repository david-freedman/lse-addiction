<?php

namespace App\Domains\Quiz\Models;

use App\Domains\Lesson\Models\Lesson;
use App\Domains\Module\Models\Module;
use App\Domains\Progress\Models\StudentQuizAttempt;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'quizzable_type',
        'quizzable_id',
        'title',
        'passing_score',
        'max_attempts',
        'time_limit_minutes',
        'show_correct_answers',
        'is_survey',
    ];

    protected function casts(): array
    {
        return [
            'passing_score' => 'integer',
            'max_attempts' => 'integer',
            'time_limit_minutes' => 'integer',
            'show_correct_answers' => 'boolean',
            'is_survey' => 'boolean',
        ];
    }

    public function isSurvey(): bool
    {
        return $this->is_survey === true;
    }

    public function quizzable(): MorphTo
    {
        return $this->morphTo();
    }

    public function questions(): HasMany
    {
        return $this->hasMany(QuizQuestion::class)->orderBy('order');
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(StudentQuizAttempt::class);
    }

    public function isForModule(): bool
    {
        return $this->quizzable_type === Module::class;
    }

    public function isForLesson(): bool
    {
        return $this->quizzable_type === Lesson::class;
    }

    protected function maxScore(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->questions->sum('points')
        );
    }
}
