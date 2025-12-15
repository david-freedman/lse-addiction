<?php

namespace App\Domains\Quiz\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuizAnswer extends Model
{
    protected $fillable = [
        'question_id',
        'answer_text',
        'answer_image',
        'is_correct',
        'category',
        'order',
        'correct_order',
    ];

    protected $casts = [
        'is_correct' => 'boolean',
        'correct_order' => 'integer',
    ];

    public function question(): BelongsTo
    {
        return $this->belongsTo(QuizQuestion::class, 'question_id');
    }
}
