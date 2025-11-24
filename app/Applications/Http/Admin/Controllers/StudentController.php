<?php

namespace App\Applications\Http\Admin\Controllers;

use App\Domains\Course\Models\Course;
use App\Domains\Student\Actions\AssignStudentToCoursesAction;
use App\Domains\Student\Actions\BulkAssignStudentsAction;
use App\Domains\Student\Actions\BulkDeleteStudentsAction;
use App\Domains\Student\Actions\CreateStudentAction;
use App\Domains\Student\Actions\DeleteStudentAction;
use App\Domains\Student\Actions\RestoreStudentAction;
use App\Domains\Student\Actions\UnenrollStudentFromCourseAction;
use App\Domains\Student\Actions\UpdateStudentAction;
use App\Domains\Student\Data\AssignToCourseData;
use App\Domains\Student\Data\BulkAssignData;
use App\Domains\Student\Data\BulkDeleteData;
use App\Domains\Student\Data\CreateStudentData;
use App\Domains\Student\Data\StudentFilterData;
use App\Domains\Student\Data\UpdateStudentData;
use App\Domains\Student\Models\Student;
use App\Domains\Student\ViewModels\StudentDetailViewModel;
use App\Domains\Student\ViewModels\StudentListViewModel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentController
{
    public function index(Request $request): View
    {
        $filters = StudentFilterData::validateAndCreate($request->all());

        $viewModel = new StudentListViewModel($filters, perPage: 20);

        return view('admin.students.index', compact('viewModel'));
    }

    public function create(): View
    {
        return view('admin.students.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = CreateStudentData::validateAndCreate($request->all());

        $student = CreateStudentAction::execute($data);

        return redirect()
            ->route('admin.students.show', $student)
            ->with('success', 'Студента успішно створено');
    }

    public function show(int $id): View
    {
        $student = Student::withTrashed()->findOrFail($id);

        $viewModel = new StudentDetailViewModel($student);

        return view('admin.students.show', compact('viewModel', 'student'));
    }

    public function edit(Student $student): View
    {
        return view('admin.students.edit', compact('student'));
    }

    public function update(Request $request, Student $student): RedirectResponse
    {
        $data = UpdateStudentData::validateAndCreate(
            array_merge($request->all(), ['studentId' => $student->id])
        );

        UpdateStudentAction::execute($student, $data);

        return redirect()
            ->route('admin.students.show', $student)
            ->with('success', 'Дані студента успішно оновлено');
    }

    public function destroy(Student $student): RedirectResponse
    {
        DeleteStudentAction::execute($student);

        return redirect()
            ->route('admin.students.index')
            ->with('success', 'Студента успішно видалено');
    }

    public function restore(int $studentId): RedirectResponse
    {
        $student = Student::withTrashed()->findOrFail($studentId);

        RestoreStudentAction::execute($student);

        return redirect()
            ->route('admin.students.show', $student)
            ->with('success', 'Студента успішно відновлено');
    }

    public function assignToCourse(Request $request, Student $student): RedirectResponse
    {
        $assignments = [];

        if ($request->has('courses')) {
            foreach ($request->input('courses', []) as $courseData) {
                $assignments[] = AssignToCourseData::validateAndCreate($courseData);
            }
        }

        AssignStudentToCoursesAction::execute($student, $assignments);

        return redirect()
            ->route('admin.students.show', $student)
            ->with('success', 'Студента успішно призначено на курси');
    }

    public function unenrollFromCourse(Student $student, Course $course): RedirectResponse
    {
        UnenrollStudentFromCourseAction::execute($student, $course);

        return redirect()
            ->route('admin.students.show', $student)
            ->with('success', 'Студента відписано від курсу');
    }

    public function bulkAssign(Request $request): RedirectResponse
    {
        $data = BulkAssignData::validateAndCreate($request->all());

        $assignedCount = BulkAssignStudentsAction::execute($data);

        return redirect()
            ->back()
            ->with('success', "Призначено {$assignedCount} студентів на курс");
    }

    public function bulkDelete(Request $request): RedirectResponse
    {
        $data = BulkDeleteData::validateAndCreate($request->all());

        $deletedCount = BulkDeleteStudentsAction::execute($data);

        return redirect()
            ->back()
            ->with('success', "Видалено {$deletedCount} студентів");
    }
}
