<?php

namespace App\Domains\Course\ViewModels;

use App\Domains\Course\Data\CourseProgressData;
use App\Domains\Course\Models\Course;
use App\Domains\Progress\Enums\ProgressStatus;
use App\Domains\Student\Models\Student;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;

readonly class MyCoursesViewModel
{
    private Collection $courses;

    private Collection $allCourses;

    private ?string $currentStatus;

    private SupportCollection $progressMap;

    public function __construct(
        private Student $student,
        ?string $status = null
    ) {
        $this->currentStatus = $status;

        $this->allCourses = $student->courses()
            ->withPivot(['enrolled_at', 'status'])
            ->with(['teacher', 'tags', 'modules.lessons'])
            ->orderBy('course_student.enrolled_at', 'desc')
            ->get();

        $query = $student->courses()
            ->withPivot(['enrolled_at', 'status'])
            ->with(['teacher', 'tags', 'modules.lessons'])
            ->orderBy('course_student.enrolled_at', 'desc');

        if ($status) {
            $query->wherePivot('status', $status);
        }

        $this->courses = $query->get();
        $this->progressMap = $this->buildProgressMap();
    }

    private function buildProgressMap(): SupportCollection
    {
        $courseIds = $this->allCourses->pluck('id');

        $lessonIdsByCourse = $this->allCourses->mapWithKeys(function (Course $course) {
            return [$course->id => $course->modules->flatMap->lessons->pluck('id')->toArray()];
        });

        $completedLessonIds = $this->student->lessonProgress()
            ->where('status', ProgressStatus::Completed)
            ->pluck('lesson_id')
            ->toArray();

        return $courseIds->mapWithKeys(function (int $courseId) use ($lessonIdsByCourse, $completedLessonIds) {
            $lessonIds = $lessonIdsByCourse[$courseId] ?? [];
            $totalLessons = count($lessonIds);
            $completedLessons = count(array_intersect($lessonIds, $completedLessonIds));
            $progressPercentage = $totalLessons > 0 ? (int) round(($completedLessons / $totalLessons) * 100) : 0;

            return [$courseId => new CourseProgressData(
                courseId: $courseId,
                progressPercentage: $progressPercentage,
                lessonsCompleted: $completedLessons,
                totalLessons: $totalLessons,
            )];
        });
    }

    public function getCourseProgress(int $courseId): CourseProgressData
    {
        return $this->progressMap->get($courseId, new CourseProgressData(
            courseId: $courseId,
            progressPercentage: 0,
            lessonsCompleted: 0,
            totalLessons: 0,
        ));
    }

    public function courses(): Collection
    {
        return $this->courses;
    }

    public function hasCourses(): bool
    {
        return $this->courses->isNotEmpty();
    }

    public function hasNoCourses(): bool
    {
        return $this->courses->isEmpty();
    }

    public function coursesCount(): int
    {
        return $this->courses->count();
    }

    public function totalCount(): int
    {
        return $this->allCourses->count();
    }

    public function activeCount(): int
    {
        return $this->allCourses->where('pivot.status', 'active')->count();
    }

    public function completedCount(): int
    {
        return $this->allCourses->where('pivot.status', 'completed')->count();
    }

    public function currentStatus(): ?string
    {
        return $this->currentStatus;
    }

    public function isFilteredBy(string $status): bool
    {
        return $this->currentStatus === $status;
    }

    public function isShowingAll(): bool
    {
        return $this->currentStatus === null;
    }
}
