<?php

namespace App\Applications\Http\Student\Module\Controllers;

use App\Domains\Course\Models\Course;
use App\Domains\Module\Models\Module;
use App\Domains\Module\ViewModels\ModuleDetailViewModel;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class ShowModuleController
{
    public function __invoke(Request $request, Course $course, Module $module): View
    {
        $student = $request->user();

        if (!$student->hasAccessToCourse($course)) {
            abort(403, 'Ви не маєте доступу до цього курсу');
        }

        if ($module->course_id !== $course->id) {
            throw new NotFoundHttpException('Модуль не належить до цього курсу');
        }

        $module->load(['lessons' => function ($query) {
            $query->published()->ordered();
        }]);

        $viewModel = new ModuleDetailViewModel($module, $course, $student);

        if (!$viewModel->isUnlocked()) {
            return view('student.modules.locked', compact('viewModel', 'course', 'module'));
        }

        return view('student.modules.show', compact('viewModel', 'course', 'module'));
    }
}
