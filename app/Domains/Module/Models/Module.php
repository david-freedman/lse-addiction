<?php

namespace App\Domains\Module\Models;

use App\Domains\Course\Models\Course;
use App\Domains\Lesson\Enums\LessonStatus;
use App\Domains\Lesson\Models\Lesson;
use App\Domains\Module\Enums\ModuleStatus;
use App\Domains\Module\Enums\ModuleUnlockRule;
use App\Domains\Progress\Enums\ProgressStatus;
use App\Domains\Progress\Models\StudentModuleProgress;
use App\Domains\Student\Models\Student;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Module extends Model
{
    use HasFactory;
    protected $fillable = [
        'course_id',
        'name',
        'description',
        'order',
        'status',
        'unlock_rule',
    ];

    protected function casts(): array
    {
        return [
            'status' => ModuleStatus::class,
            'unlock_rule' => ModuleUnlockRule::class,
        ];
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class)->orderBy('order');
    }

    public function regularLessons(): HasMany
    {
        return $this->hasMany(Lesson::class)->where('is_final', false)->orderBy('order');
    }

    public function progress(): HasMany
    {
        return $this->hasMany(StudentModuleProgress::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', ModuleStatus::Active);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    public function isUnlocked(Student $student): bool
    {
        if ($this->unlock_rule === ModuleUnlockRule::None) {
            return true;
        }

        $previousModule = $this->getPreviousModule();
        if (!$previousModule) {
            return true;
        }

        $previousProgress = $previousModule->progress()
            ->where('student_id', $student->id)
            ->first();

        return match ($this->unlock_rule) {
            ModuleUnlockRule::CompletePrevious => $previousProgress?->status === ProgressStatus::Completed,
            ModuleUnlockRule::CompleteTest => $this->checkPreviousModuleFinalTestPassed($previousModule, $student),
            default => true,
        };
    }

    private function checkPreviousModuleFinalTestPassed(Module $previousModule, Student $student): bool
    {
        $finalTestLesson = $previousModule->finalTestLesson();
        if (!$finalTestLesson) {
            return true;
        }

        $quiz = $finalTestLesson->quiz;
        if (!$quiz) {
            return true;
        }

        return $quiz->attempts()
            ->where('student_id', $student->id)
            ->where('passed', true)
            ->exists();
    }

    public function getPreviousModule(): ?Module
    {
        return $this->course->modules()
            ->where('order', '<', $this->order)
            ->orderByDesc('order')
            ->first();
    }

    protected function lessonsCount(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->lessons()->count()
        );
    }

    protected function publishedLessonsCount(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->lessons()->where('status', LessonStatus::Published)->count()
        );
    }

    public function hasFinalTest(): bool
    {
        return $this->lessons()->where('is_final', true)->exists();
    }

    public function finalTestLesson(): ?Lesson
    {
        return $this->lessons()->where('is_final', true)->first();
    }

    public function isCompleted(Student $student): bool
    {
        $regularLessons = $this->regularLessons()
            ->where('status', LessonStatus::Published)
            ->get();

        foreach ($regularLessons as $lesson) {
            $progress = $lesson->progress()
                ->where('student_id', $student->id)
                ->where('status', ProgressStatus::Completed)
                ->exists();

            if (!$progress) {
                return false;
            }
        }

        $finalTest = $this->finalTestLesson();
        if ($finalTest && $finalTest->status === LessonStatus::Published) {
            $quiz = $finalTest->quiz;
            if ($quiz) {
                return $quiz->attempts()
                    ->where('student_id', $student->id)
                    ->where('passed', true)
                    ->exists();
            }
        }

        return true;
    }

    public function allRegularLessonsCompleted(Student $student): bool
    {
        $regularLessons = $this->regularLessons()
            ->where('status', LessonStatus::Published)
            ->get();

        foreach ($regularLessons as $lesson) {
            $completed = $lesson->progress()
                ->where('student_id', $student->id)
                ->where('status', ProgressStatus::Completed)
                ->exists();

            if (!$completed) {
                return false;
            }
        }

        return true;
    }
}
