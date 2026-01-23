<?php

namespace App\Applications\Http\Admin\Course\Controllers;

use App\Domains\Course\Models\Course;
use App\Domains\Course\ViewModels\CourseHistoryViewModel;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class GetCourseHistoryController
{
    public function __invoke(Request $request, Course $course): View
    {
        $viewModel = new CourseHistoryViewModel(
            course: $course,
            subjectType: $request->query('subject_type'),
            performedBy: $request->query('performed_by') ? (int) $request->query('performed_by') : null,
            dateFrom: $request->query('date_from'),
            dateTo: $request->query('date_to'),
        );

        return view('admin.courses.history', [
            'viewModel' => $viewModel,
            'course' => $course,
            'breadcrumbs' => [
                ['title' => 'Курси', 'url' => route('admin.courses.index')],
                ['title' => $course->name, 'url' => route('admin.courses.show', $course)],
                ['title' => 'Історія змін'],
            ],
        ]);
    }
}
