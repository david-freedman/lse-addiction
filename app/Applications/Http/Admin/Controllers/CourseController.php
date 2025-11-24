<?php

namespace App\Applications\Http\Admin\Controllers;

use App\Domains\Course\Actions\CreateCourseAction;
use App\Domains\Course\Actions\DeleteCourseAction;
use App\Domains\Course\Actions\UpdateCourseAction;
use App\Domains\Course\Data\CreateCourseData;
use App\Domains\Course\Data\UpdateCourseData;
use App\Domains\Course\Models\Course;
use App\Domains\Course\ViewModels\CourseDetailViewModel;
use App\Domains\Course\ViewModels\CourseListViewModel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CourseController
{
    public function index(Request $request): View
    {
        $courses = Course::with(['coach', 'tags'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $viewModel = new CourseListViewModel($courses);

        return view('admin.courses.index', compact('viewModel'));
    }

    public function show(Course $course): View
    {
        $course->load(['coach', 'author', 'tags', 'students']);

        $viewModel = new CourseDetailViewModel($course);

        return view('admin.courses.show', compact('viewModel', 'course'));
    }

    public function create(): View
    {
        return view('admin.courses.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = CreateCourseData::validateAndCreate($request->all());

        $course = CreateCourseAction::execute($data);

        return redirect()
            ->route('admin.courses.show', $course)
            ->with('success', 'Курс успішно створено');
    }

    public function edit(Course $course): View
    {
        $course->load(['tags', 'author']);

        return view('admin.courses.edit', compact('course'));
    }

    public function update(Request $request, Course $course): RedirectResponse
    {
        $data = UpdateCourseData::validateAndCreate($request->all());

        UpdateCourseAction::execute($course, $data);

        return redirect()
            ->route('admin.courses.show', $course)
            ->with('success', 'Курс успішно оновлено');
    }

    public function destroy(Course $course): RedirectResponse
    {
        DeleteCourseAction::execute($course);

        return redirect()
            ->route('admin.courses.index')
            ->with('success', 'Курс успішно видалено');
    }
}
