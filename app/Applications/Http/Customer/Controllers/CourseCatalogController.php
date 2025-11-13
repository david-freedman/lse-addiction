<?php

namespace App\Applications\Http\Customer\Controllers;

use App\Domains\Course\Actions\PurchaseCourseAction;
use App\Domains\Course\Enums\CourseType;
use App\Domains\Course\Models\Course;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CourseCatalogController
{
    public function index(Request $request): View
    {
        $customer = auth()->user();

        $type = $request->input('type');

        $courses = Course::with(['coach', 'tags'])
            ->availableForPurchase($customer)
            ->when($type, function ($query, $type) {
                if ($type === 'recorded') {
                    return $query->where('type', CourseType::Recorded);
                }
                if ($type === 'upcoming') {
                    return $query->where('type', CourseType::Upcoming);
                }
            })
            ->orderBy('created_at', 'desc')
            ->paginate(9);

        $recordedCount = Course::availableForPurchase($customer)
            ->where('type', CourseType::Recorded)
            ->count();

        $upcomingCount = Course::availableForPurchase($customer)
            ->where('type', CourseType::Upcoming)
            ->count();

        return view('customer.catalog.index', compact('courses', 'recordedCount', 'upcomingCount'));
    }

    public function show(Course $course): View
    {
        $course->load(['coach', 'author', 'tags']);

        $customer = auth()->user();

        if ($course->hasCustomer($customer)) {
            return redirect()
                ->route('courses.show', $course)
                ->with('info', 'Ви вже записані на цей курс');
        }

        return view('customer.catalog.show', compact('course'));
    }

    public function purchase(Course $course): RedirectResponse
    {
        $customer = auth()->user();

        if ($course->hasCustomer($customer)) {
            return redirect()
                ->back()
                ->with('error', 'Ви вже придбали цей курс');
        }

        if (!$course->isPublished()) {
            return redirect()
                ->back()
                ->with('error', 'Цей курс недоступний для покупки');
        }

        PurchaseCourseAction::execute($course, $customer);

        return redirect()
            ->route('courses.show', $course)
            ->with('success', 'Курс успішно придбано! Приємного навчання!');
    }
}
