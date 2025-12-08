<?php

namespace App\Applications\Http\Student\Catalog\Controllers;

use App\Domains\Course\Enums\CourseStatus;
use App\Domains\Course\Enums\CourseType;
use App\Domains\Course\Models\Course;
use App\Domains\Discount\Models\StudentCourseDiscount;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class GetCatalogController
{
    public function __invoke(Request $request): View
    {
        $student = auth()->user();

        $type = $request->input('type');
        $search = $request->input('search');

        $courses = Course::with(['teacher', 'tags'])
            ->where('status', CourseStatus::Active)
            ->when($type, function ($query, $type) {
                if ($type === 'recorded') {
                    return $query->where('type', CourseType::Recorded);
                }
                if ($type === 'upcoming') {
                    return $query->where('type', CourseType::Upcoming);
                }
            })
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

        $recordedCount = Course::where('status', CourseStatus::Active)
            ->where('type', CourseType::Recorded)
            ->count();

        $upcomingCount = Course::where('status', CourseStatus::Active)
            ->where('type', CourseType::Upcoming)
            ->count();

        return view('student.catalog.index', compact('courses', 'recordedCount', 'upcomingCount'));
    }
}
