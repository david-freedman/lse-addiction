<?php

namespace App\Applications\Http\Student\Controllers;

use App\Domains\Course\Actions\CompleteCoursePurchaseAction;
use App\Domains\Course\Actions\PurchaseCourseAction;
use App\Domains\Course\Enums\CourseStatus;
use App\Domains\Course\Enums\CourseType;
use App\Domains\Course\Models\Course;
use App\Domains\Transaction\Actions\CompleteTransactionAction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CourseCatalogController
{
    public function index(Request $request): View
    {
        $student = auth()->user();

        $type = $request->input('type');
        $search = $request->input('search');

        $courses = Course::with(['coach', 'tags'])
            ->where('status', CourseStatus::Published)
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

        $courses->each(function ($course) use ($purchasedCourseIds) {
            $course->is_purchased = in_array($course->id, $purchasedCourseIds);
        });

        $recordedCount = Course::where('status', CourseStatus::Published)
            ->where('type', CourseType::Recorded)
            ->count();

        $upcomingCount = Course::where('status', CourseStatus::Published)
            ->where('type', CourseType::Upcoming)
            ->count();

        return view('student.catalog.index', compact('courses', 'recordedCount', 'upcomingCount'));
    }

    public function show(Course $course): View
    {
        $course->load(['coach', 'author', 'tags']);

        return view('student.catalog.show', compact('course'));
    }

    public function purchase(Course $course): RedirectResponse
    {
        $student = auth()->user();

        if ($course->hasStudent($student)) {
            return redirect()
                ->back()
                ->with('error', 'Ви вже придбали цей курс');
        }

        if (! $course->isPublished()) {
            return redirect()
                ->back()
                ->with('error', 'Цей курс недоступний для покупки');
        }

        if ($course->price == 0) {
            $transaction = PurchaseCourseAction::execute($course, $student);
            CompleteTransactionAction::execute($transaction);
            CompleteCoursePurchaseAction::execute($transaction);

            return redirect()
                ->route('student.courses.show', $course)
                ->with('success', 'Курс успішно придбано! Приємного навчання!');
        }

        $transaction = PurchaseCourseAction::execute($course, $student);

        return redirect()
            ->route('student.payment.initiate', $transaction)
            ->with('info', 'Будь ласка, завершіть оплату для отримання доступу до курсу');
    }
}
