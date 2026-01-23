<?php

namespace App\Applications\Http\Student\Catalog\Controllers;

use App\Domains\Course\Enums\CourseStatus;
use App\Domains\Course\Models\Course;
use App\Domains\Discount\Models\StudentCourseDiscount;
use App\Domains\Webinar\Enums\WebinarStatus;
use App\Domains\Webinar\Models\Webinar;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class GetCatalogController
{
    public function __invoke(Request $request): View
    {
        $student = auth()->user();
        $tab = $request->input('tab', 'courses');
        $search = $request->input('search');
        $webinarFilter = $request->input('webinar_filter', 'all');

        if ($tab === 'webinars') {
            return $this->webinarsTab($student, $search, $webinarFilter);
        }

        return $this->coursesTab($student, $search);
    }

    private function coursesTab($student, ?string $search): View
    {
        $courses = Course::with(['teacher', 'tags'])
            ->where('status', CourseStatus::Active)
            ->when($search, function ($query, $search) {
                return $query->where('name', 'ilike', "%{$search}%");
            })
            ->orderBy('updated_at', 'desc')
            ->paginate(9)
            ->withQueryString();

        $purchasedCourseIds = $student->courses()->pluck('courses.id')->toArray();

        $discounts = StudentCourseDiscount::forStudent($student->id)
            ->active()
            ->get()
            ->keyBy('course_id');

        $courses->each(function ($course) use ($purchasedCourseIds, $discounts) {
            $course->is_purchased = in_array($course->id, $purchasedCourseIds);
            $course->individual_discount = $discounts->get($course->id);
        });

        $coursesCount = Course::where('status', CourseStatus::Active)->count();
        $webinarsCount = $this->getWebinarsCount();

        return view('student.catalog.index', [
            'tab' => 'courses',
            'courses' => $courses,
            'webinars' => null,
            'coursesCount' => $coursesCount,
            'webinarsCount' => $webinarsCount,
            'webinarFilter' => 'all',
            'liveWebinarsCount' => $this->getLiveWebinarsCount(),
            'recordedWebinarsCount' => $this->getRecordedWebinarsCount(),
        ]);
    }

    private function webinarsTab($student, ?string $search, string $webinarFilter): View
    {
        $query = Webinar::with(['teacher.user'])
            ->when($search, function ($query, $search) {
                return $query->where('title', 'ilike', "%{$search}%");
            })
            ->withCount('activeRegistrations as participants_count');

        if ($webinarFilter === 'live') {
            $query->where(function ($q) {
                $q->where(function ($sub) {
                    $sub->where('status', WebinarStatus::Upcoming)
                        ->where('starts_at', '>', now());
                })->orWhere('status', WebinarStatus::Live);
            })->orderBy('starts_at');
        } elseif ($webinarFilter === 'recorded') {
            $query->where('status', WebinarStatus::Recorded)
                ->orderBy('starts_at', 'desc');
        } else {
            $query->where(function ($q) {
                $q->where(function ($sub) {
                    $sub->where('status', WebinarStatus::Upcoming)
                        ->where('starts_at', '>', now());
                })->orWhere('status', WebinarStatus::Live)
                    ->orWhere('status', WebinarStatus::Recorded);
            })
                ->orderByRaw("CASE WHEN status IN ('upcoming', 'live') THEN 0 ELSE 1 END")
                ->orderByRaw("CASE WHEN status IN ('upcoming', 'live') THEN starts_at END ASC")
                ->orderByRaw("CASE WHEN status = 'recorded' THEN starts_at END DESC");
        }

        $webinars = $query->paginate(9)->withQueryString();

        $registeredWebinarIds = $student->webinars()
            ->wherePivotNull('cancelled_at')
            ->pluck('webinars.id')
            ->toArray();

        $webinars->each(function ($webinar) use ($registeredWebinarIds) {
            $webinar->is_registered = in_array($webinar->id, $registeredWebinarIds);
        });

        $coursesCount = Course::where('status', CourseStatus::Active)->count();
        $webinarsCount = $this->getWebinarsCount();

        return view('student.catalog.index', [
            'tab' => 'webinars',
            'courses' => null,
            'webinars' => $webinars,
            'coursesCount' => $coursesCount,
            'webinarsCount' => $webinarsCount,
            'webinarFilter' => $webinarFilter,
            'liveWebinarsCount' => $this->getLiveWebinarsCount(),
            'recordedWebinarsCount' => $this->getRecordedWebinarsCount(),
        ]);
    }

    private function getWebinarsCount(): int
    {
        return $this->getLiveWebinarsCount() + $this->getRecordedWebinarsCount();
    }

    private function getLiveWebinarsCount(): int
    {
        return Webinar::where(function ($q) {
            $q->where(function ($sub) {
                $sub->where('status', WebinarStatus::Upcoming)
                    ->where('starts_at', '>', now());
            })->orWhere('status', WebinarStatus::Live);
        })->count();
    }

    private function getRecordedWebinarsCount(): int
    {
        return Webinar::where('status', WebinarStatus::Recorded)->count();
    }
}
