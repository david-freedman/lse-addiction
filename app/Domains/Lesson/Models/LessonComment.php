<?php

namespace App\Domains\Lesson\Models;

use App\Domains\Student\Models\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class LessonComment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'student_id',
        'user_id',
        'lesson_id',
        'parent_id',
        'content',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(LessonComment::class, 'parent_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(LessonComment::class, 'parent_id');
    }

    public function isFromStudent(): bool
    {
        return $this->student_id !== null;
    }

    public function isFromUser(): bool
    {
        return $this->user_id !== null;
    }

    public function authorName(): string
    {
        if ($this->isFromStudent()) {
            $student = $this->student;
            if ($student) {
                return trim($student->name . ' ' . $student->surname) ?: 'Студент';
            }
            return 'Студент';
        }

        return $this->user?->name ?? 'Адміністратор';
    }

    public function authorInitials(): string
    {
        $name = $this->authorName();
        $parts = explode(' ', $name);

        if (count($parts) >= 2) {
            return mb_substr($parts[0], 0, 1) . mb_substr($parts[1], 0, 1);
        }

        return mb_substr($name, 0, 2);
    }

    public function isReply(): bool
    {
        return $this->parent_id !== null;
    }
}
