<?php

namespace App\Domains\Quiz\Models;

use App\Domains\Quiz\Enums\QuestionType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuizQuestion extends Model
{
    protected $fillable = [
        'quiz_id',
        'type',
        'question_text',
        'question_image',
        'order',
        'points',
    ];

    protected function casts(): array
    {
        return [
            'type' => QuestionType::class,
        ];
    }

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(QuizAnswer::class, 'question_id')->orderBy('order');
    }

    public function correctAnswers(): HasMany
    {
        return $this->answers()->where('is_correct', true);
    }
}
