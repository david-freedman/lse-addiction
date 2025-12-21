<?php

namespace App\Domains\Dashboard\ViewModels;

use App\Domains\Calendar\Data\CalendarCourseData;
use App\Domains\Calendar\Data\CalendarWebinarData;
use App\Domains\Course\Enums\CourseType;
use App\Domains\Course\Enums\CourseStatus;
use App\Domains\Course\Models\Course;
use App\Domains\Dashboard\Data\DashboardCourseData;
use App\Domains\Dashboard\Data\DashboardStatsData;
use App\Domains\Dashboard\Data\DashboardWebinarData;
use App\Domains\Lesson\Models\Lesson;
use App\Domains\Module\Models\Module;
use App\Domains\Progress\Enums\ProgressStatus;
use App\Domains\Student\Models\Student;
use App\Domains\Webinar\Enums\WebinarStatus;
use App\Domains\Webinar\Models\Webinar;
use Carbon\Carbon;
use Illuminate\Support\Collection;

readonly class StudentDashboardViewModel
{
    public function __construct(
        private Student $student
    ) {}

    public function greeting(): string
    {
        $hour = (int) now()->format('H');

        $timeOfDay = match (true) {
            $hour >= 5 && $hour < 12 => 'Добрий ранок',
            $hour >= 12 && $hour < 18 => 'Добрий день',
            default => 'Добрий вечір',
        };

        return "{$timeOfDay}, {$this->student->name}!";
    }

    public function welcomeMessage(): string
    {
        return 'Ласкаво просимо на освітню платформу LSE. Продовжуйте навчання і розвивайтеся разом з нами.';
    }

    public function stats(): DashboardStatsData
    {
        $weekStart = now()->startOfWeek();

        $coursesInProgress = $this->student->courses()
            ->where('courses.status', '!=', CourseStatus::Hidden)
            ->wherePivot('status', 'active')
            ->count();

        $coursesInProgressDelta = $this->student->courses()
            ->where('courses.status', '!=', CourseStatus::Hidden)
            ->wherePivot('status', 'active')
            ->wherePivot('enrolled_at', '>=', $weekStart)
            ->count();

        $completedCourses = $this->student->courseProgress()
            ->where('status', ProgressStatus::Completed)
            ->count();

        $completedCoursesDelta = $this->student->courseProgress()
            ->where('status', ProgressStatus::Completed)
            ->where('completed_at', '>=', $weekStart)
            ->count();

        $studyMinutes = $this->calculateStudyMinutes();
        $studyMinutesThisWeek = $this->calculateStudyMinutes($weekStart);

        $certificates = $this->student->certificates()->count();
        $certificatesDelta = $this->student->certificates()
            ->where('issued_at', '>=', $weekStart)
            ->count();

        return new DashboardStatsData(
            coursesInProgress: $coursesInProgress,
            coursesInProgressDelta: $coursesInProgressDelta,
            completedCourses: $completedCourses,
            completedCoursesDelta: $completedCoursesDelta,
            studyHours: (int) floor($studyMinutes / 60),
            studyHoursDelta: (int) floor($studyMinutesThisWeek / 60),
            certificates: $certificates,
            certificatesDelta: $certificatesDelta,
        );
    }

    private function calculateStudyMinutes(?\DateTimeInterface $since = null): int
    {
        $query = $this->student->lessonProgress()
            ->where('student_lesson_progress.status', ProgressStatus::Completed)
            ->join('lessons', 'student_lesson_progress.lesson_id', '=', 'lessons.id');

        if ($since) {
            $query->where('student_lesson_progress.completed_at', '>=', $since);
        }

        return (int) $query->sum('lessons.duration_minutes');
    }

    /**
     * @return Collection<int, DashboardCourseData>
     */
    public function courses(): Collection
    {
        $courses = $this->student->courses()
            ->where('courses.status', '!=', CourseStatus::Hidden)
            ->wherePivot('status', 'active')
            ->with([
                'modules' => fn ($q) => $q->active()->ordered()
                    ->with(['lessons' => fn ($l) => $l->published()->ordered()]),
            ])
            ->orderBy('course_student.enrolled_at', 'desc')
            ->limit(3)
            ->get();

        return $courses->map(fn (Course $course) => $this->buildCourseData($course));
    }

    public function coursesCount(): int
    {
        return $this->student->courses()
            ->where('courses.status', '!=', CourseStatus::Hidden)
            ->wherePivot('status', 'active')
            ->count();
    }

    private function buildCourseData(Course $course): DashboardCourseData
    {
        $lessonIds = $course->modules
            ->flatMap(fn (Module $m) => $m->lessons->pluck('id'));

        $completedCount = $this->student->lessonProgress()
            ->whereIn('lesson_id', $lessonIds)
            ->where('status', ProgressStatus::Completed)
            ->count();

        $totalLessons = $lessonIds->count();
        $progressPercentage = $totalLessons > 0
            ? (int) round(($completedCount / $totalLessons) * 100)
            : 0;

        return new DashboardCourseData(
            id: $course->id,
            name: $course->name,
            slug: $course->slug,
            lessonsCompleted: $completedCount,
            totalLessons: $totalLessons,
            progressPercentage: $progressPercentage,
            continueUrl: route('student.courses.show', $course->slug),
        );
    }

    /**
     * @return Collection<int, DashboardWebinarData>
     */
    public function upcomingWebinars(): Collection
    {
        $myWebinars = $this->student->webinars()
            ->whereIn('status', [WebinarStatus::Upcoming, WebinarStatus::Live])
            ->ordered()
            ->with('teacher')
            ->limit(2)
            ->get();

        if ($myWebinars->isNotEmpty()) {
            return $myWebinars->map(fn (Webinar $webinar) => $this->buildWebinarData($webinar, [$webinar->id]));
        }

        $webinars = Webinar::query()
            ->whereIn('status', [WebinarStatus::Upcoming, WebinarStatus::Live])
            ->ordered()
            ->with('teacher')
            ->limit(2)
            ->get();

        $registeredIds = $this->student->webinars()->pluck('webinars.id')->toArray();

        return $webinars->map(fn (Webinar $webinar) => $this->buildWebinarData($webinar, $registeredIds));
    }

    public function webinarsCount(): int
    {
        return Webinar::query()->upcoming()->count();
    }

    public function hasWebinars(): bool
    {
        return $this->webinarsCount() > 0;
    }

    private function buildWebinarData(Webinar $webinar, array $registeredIds): DashboardWebinarData
    {
        return new DashboardWebinarData(
            id: $webinar->id,
            title: $webinar->title,
            slug: $webinar->slug,
            teacherName: $webinar->teacher->full_name,
            teacherPhotoUrl: $webinar->teacher->avatar_url,
            formattedDate: $webinar->formatted_date,
            formattedTime: $webinar->formatted_time,
            formattedDuration: $webinar->formatted_duration,
            formattedDatetime: $webinar->formatted_date . ', ' . $webinar->formatted_time,
            participantsCount: $webinar->participantsCount(),
            isStartingSoon: $webinar->is_starting_soon,
            isRegistered: in_array($webinar->id, $registeredIds),
            isLive: $webinar->status === WebinarStatus::Live,
            isUpcoming: $webinar->status === WebinarStatus::Upcoming,
            isCompleted: $webinar->status === WebinarStatus::Completed,
            status: $webinar->status,
            price: $webinar->price > 0 ? number_format($webinar->price, 0, '', ' ') . ' грн' : 'Безкоштовно',
            isFree: $webinar->is_free,
            bannerUrl: $webinar->banner_url,
            availableSpots: $webinar->available_spots,
        );
    }

    public function calendarData(): array
    {
        $registeredIds = $this->student->webinars()->pluck('webinars.id')->toArray();

        $months = collect(range(0, 5))->map(fn (int $i) => now()->addMonths($i));
        $lastMonth = $months->last();

        $webinars = Webinar::query()
            ->upcoming()
            ->ordered()
            ->with('teacher')
            ->where('starts_at', '<=', $lastMonth->endOfMonth())
            ->get();

        $courses = Course::query()
            ->where('status', CourseStatus::Active)
            ->where('type', CourseType::Upcoming)
            ->whereNotNull('starts_at')
            ->where('starts_at', '>', now())
            ->where('starts_at', '<=', $lastMonth->endOfMonth())
            ->orderBy('starts_at')
            ->with('teacher')
            ->get();

        $webinarsByDate = [];
        $datesWithWebinars = [];

        foreach ($webinars as $webinar) {
            $dateKey = $webinar->starts_at->format('Y-m-d');

            if (!isset($webinarsByDate[$dateKey])) {
                $webinarsByDate[$dateKey] = [];
                $datesWithWebinars[] = $dateKey;
            }

            $webinarsByDate[$dateKey][] = $this->buildCalendarWebinarData($webinar, $registeredIds);
        }

        $coursesByDate = [];
        $datesWithCourses = [];

        foreach ($courses as $course) {
            $dateKey = $course->starts_at->format('Y-m-d');

            if (!isset($coursesByDate[$dateKey])) {
                $coursesByDate[$dateKey] = [];
                $datesWithCourses[] = $dateKey;
            }

            $coursesByDate[$dateKey][] = $this->buildCalendarCourseData($course);
        }

        $monthsData = $months->map(
            fn ($date) => $this->buildMonthData($date->year, $date->month)
        )->values()->all();

        $upcomingWebinars = $webinars->take(2)->map(
            fn (Webinar $webinar) => $this->buildCalendarWebinarData($webinar, $registeredIds)
        )->values()->all();

        $upcomingCourses = $courses->take(2)->map(
            fn (Course $course) => $this->buildCalendarCourseData($course)
        )->values()->all();

        return [
            'months' => $monthsData,
            'webinarsByDate' => $webinarsByDate,
            'datesWithWebinars' => $datesWithWebinars,
            'upcomingWebinars' => $upcomingWebinars,
            'coursesByDate' => $coursesByDate,
            'datesWithCourses' => $datesWithCourses,
            'upcomingCourses' => $upcomingCourses,
        ];
    }

    private function buildMonthData(int $year, int $month): array
    {
        $date = Carbon::create($year, $month, 1);

        return [
            'year' => $year,
            'month' => $month,
            'name' => $date->translatedFormat('F Y'),
        ];
    }

    private function buildCalendarWebinarData(Webinar $webinar, array $registeredIds): CalendarWebinarData
    {
        return new CalendarWebinarData(
            id: $webinar->id,
            title: $webinar->title,
            slug: $webinar->slug,
            teacherName: $webinar->teacher->full_name,
            teacherPhotoUrl: $webinar->teacher->avatar_url,
            date: $webinar->starts_at->format('Y-m-d'),
            formattedDate: $webinar->formatted_date,
            formattedTime: $webinar->formatted_time,
            formattedDuration: $webinar->formatted_duration,
            participantsCount: $webinar->participantsCount(),
            isRegistered: in_array($webinar->id, $registeredIds),
        );
    }

    private function buildCalendarCourseData(Course $course): CalendarCourseData
    {
        return new CalendarCourseData(
            id: $course->id,
            name: $course->name,
            slug: $course->slug,
            teacherName: $course->teacher->full_name,
            teacherPhotoUrl: $course->teacher->avatar_url,
            date: $course->starts_at->format('Y-m-d'),
            formattedDate: $course->formatted_date,
            label: $course->label_text,
        );
    }
}
