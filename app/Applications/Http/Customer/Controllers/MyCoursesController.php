<?php

namespace App\Applications\Http\Customer\Controllers;

use App\Domains\Course\Actions\EnrollCustomerAction;
use App\Domains\Course\Actions\UnenrollCustomerAction;
use App\Domains\Course\Models\Course;
use App\Domains\Course\ViewModels\CourseDetailViewModel;
use App\Domains\Course\ViewModels\MyCoursesViewModel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MyCoursesController
{
    public function index(Request $request): View
    {
        $customer = $request->user();
        $viewModel = new MyCoursesViewModel($customer);

        return view('customer.courses.index', compact('viewModel'));
    }

    public function show(Request $request, Course $course): View
    {
        $customer = $request->user();
        $course->load(['coach', 'author', 'tags']);

        $viewModel = new CourseDetailViewModel($course, $customer);

        return view('customer.courses.show', compact('viewModel', 'course'));
    }

    public function enroll(Request $request, Course $course): RedirectResponse
    {
        $customer = $request->user();

        if ($course->status !== 'published') {
            return redirect()
                ->back()
                ->with('error', 'Цей курс недоступний для запису');
        }

        EnrollCustomerAction::execute($course, $customer);

        return redirect()
            ->route('customer.my-courses')
            ->with('success', 'Ви успішно записалися на курс');
    }

    public function unenroll(Request $request, Course $course): RedirectResponse
    {
        $customer = $request->user();

        UnenrollCustomerAction::execute($course, $customer);

        return redirect()
            ->route('customer.my-courses')
            ->with('success', 'Ви успішно скасували запис на курс');
    }
}
